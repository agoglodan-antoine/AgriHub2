<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Annonce;
use App\Models\User;
use App\Models\MessagePieceJointe;
use App\Services\MessageLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class MessageController extends Controller
{
    const MAX_IMAGE_SIZE = 5 * 1024 * 1024;
    const MAX_VIDEO_SIZE = 10 * 1024 * 1024;
    const MAX_DOCUMENT_SIZE = 5 * 1024 * 1024;
    const MAX_AUDIO_SIZE = 10 * 1024 * 1024;
    const MAX_TOTAL_FILES = 5;

    const ALLOWED_IMAGES = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'];
    const ALLOWED_VIDEOS = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm', '3gp', 'm4v', 'mpeg'];
    const ALLOWED_AUDIOS = ['mp3', 'wav', 'ogg', 'aac', 'flac', 'opus'];
    const ALLOWED_DOCUMENTS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv', 'zip', 'rar', '7z'];

    /**
     * Afficher la liste des discussions
     */
    public function index()
    {
        try {
            $userId = Auth::id();

            $discussions = Message::where('id_expediteur', $userId)
                ->orWhere('id_destinataire', $userId)
                ->with(['expediteur', 'destinataire', 'annonce'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy(function($message) use ($userId) {
                    return $message->getOtherUser($userId);
                })
                ->map(function($messages) use ($userId) {
                    $first = $messages->first();
                    $last = $messages->last();
                    return (object) [
                        'user' => $first->expediteur->id === $userId ? $first->destinataire : $first->expediteur,
                        'annonce' => $first->annonce,
                        'last_message' => $last,
                        'unread_count' => $messages->where('id_destinataire', $userId)->where('lu', false)->count(),
                        'messages' => $messages
                    ];
                })
                ->sortByDesc(function($discussion) {
                    return $discussion->last_message->created_at;
                });

            MessageLogger::logSuccess('AFFICHAGE_DISCUSSIONS', null, [
                'info' => "Liste des discussions chargée (" . $discussions->count() . " discussions)"
            ]);

            return view('messages.index', compact('discussions'));

        } catch (Throwable $e) {
            MessageLogger::logError('AFFICHAGE_DISCUSSIONS', null, $e);
            throw $e;
        }
    }

    /**
     * Afficher une discussion spécifique
     */
    public function show($userId)
    {
        try {
            $currentUserId = Auth::id();
            
            $otherUser = User::findOrFail($userId);

            $messages = Message::conversation($currentUserId, $userId)
                ->with(['expediteur', 'destinataire', 'annonce', 'piecesJointes', 'reponseA.expediteur'])
                ->orderBy('created_at', 'asc')
                ->get();

            $messagesCount = $messages->count();

            // Marquer les messages comme lus
            $updatedCount = Message::where('id_destinataire', $currentUserId)
                ->where('id_expediteur', $userId)
                ->update(['lu' => true]);

            $annonce = null;
            if ($messages->isNotEmpty()) {
                $firstMessage = $messages->first();
                if ($firstMessage->id_annonce) {
                    $annonce = Annonce::with(['auteur', 'piecesJointes'])->find($firstMessage->id_annonce);
                }
            }

            MessageLogger::logSuccess('AFFICHAGE_CONVERSATION', null, [
                'info' => "Conversation avec ID={$userId} ({$messagesCount} messages, {$updatedCount} marqués comme lus)"
            ]);

            return view('messages.show', compact('messages', 'otherUser', 'annonce'));

        } catch (Throwable $e) {
            MessageLogger::logError('AFFICHAGE_CONVERSATION', null, $e, [
                'target_user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Envoyer un nouveau message avec pièces jointes (AJAX)
     */
    public function send(Request $request)
    {
        $message = null;
        
        try {
            // Validation
            $validator = $this->validateMessageRequest($request);

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                MessageLogger::logError('ENVOI_MESSAGE', null, null, [
                    'error' => 'Validation échouée: ' . json_encode($errors)
                ]);
                
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 422);
            }

            // Récupérer les données audio/vidéo
            $audioData = $request->input('audio_data', '');
            $videoData = $request->input('video_data', '');
            
            $hasAudio = !empty($audioData) && strpos($audioData, 'base64') !== false;
            $hasVideo = !empty($videoData) && strpos($videoData, 'base64') !== false;
            $hasContent = !empty(trim($request->contenu ?? ''));
            
            $hasFiles = false;
            $fileNames = [];
            if ($request->hasFile('pieces_jointes')) {
                $files = $request->file('pieces_jointes');
                $validFiles = array_filter($files, function($file) {
                    return $file->isValid();
                });
                $hasFiles = count($validFiles) > 0;
                foreach ($validFiles as $file) {
                    $fileNames[] = $file->getClientOriginalName();
                }
            }

            // Vérifier qu'il y a au moins un contenu
            if (!$hasContent && !$hasFiles && !$hasAudio && !$hasVideo) {
                MessageLogger::logError('ENVOI_MESSAGE', null, null, [
                    'error' => 'Message vide (aucun contenu, fichier, audio ou vidéo)'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez saisir un message, joindre un fichier, ou enregistrer un audio/vidéo.'
                ], 422);
            }

            DB::beginTransaction();

            // Créer le message
            $message = Message::create([
                'id_expediteur' => Auth::id(),
                'id_destinataire' => $request->id_destinataire,
                'id_annonce' => $request->id_annonce,
                'contenu' => trim($request->contenu ?? ''),
                'date_envoi' => now(),
                'est_reponse' => !empty($request->reponse_a_id),
                'reponse_a_id' => $request->reponse_a_id,
                'lu' => false,
                'has_pieces_jointes' => false,
            ]);

            $hasPieces = false;
            $uploadedFiles = [];

            // 1. Gérer les fichiers uploadés
            if ($request->hasFile('pieces_jointes')) {
                foreach ($request->file('pieces_jointes') as $file) {
                    if (!$file->isValid()) {
                        continue;
                    }
                    
                    $stored = $this->storeFile($file, $message);
                    if ($stored) {
                        $hasPieces = true;
                        $uploadedFiles[] = $file->getClientOriginalName();
                    }
                }
            }

            // 2. Gérer l'enregistrement audio
            if ($hasAudio) {
                $stored = $this->storeAudioOpus($audioData, $message);
                if ($stored) {
                    $hasPieces = true;
                    $uploadedFiles[] = 'Enregistrement audio.opus';
                }
            }

            // 3. Gérer l'enregistrement vidéo
            if ($hasVideo) {
                $stored = $this->storeVideoRecording($videoData, $message);
                if ($stored) {
                    $hasPieces = true;
                    $uploadedFiles[] = 'Enregistrement vidéo.webm';
                }
            }

            if ($hasPieces) {
                $message->update(['has_pieces_jointes' => true]);
            }

            DB::commit();

            // Charger le message avec ses relations
            $message->load(['expediteur', 'piecesJointes', 'reponseA.expediteur']);

            // Journaliser le succès
            $filesInfo = !empty($uploadedFiles) ? implode(', ', $uploadedFiles) : 'Aucun fichier';
            MessageLogger::logSuccess('ENVOI_MESSAGE', $message, [
                'files' => $filesInfo,
                'has_audio' => $hasAudio,
                'has_video' => $hasVideo,
                'info' => "Message envoyé à l'utilisateur ID={$request->id_destinataire}"
            ]);

            // Générer le HTML du message
            $html = $this->renderMessage($message);

            return response()->json([
                'success' => true,
                'message' => 'Message envoyé avec succès !',
                'html' => $html,
                'message_id' => $message->id,
                'created_at' => $message->created_at->format('d/m/Y H:i')
            ]);

        } catch (Throwable $e) {
            DB::rollBack();
            
            // Journaliser l'erreur
            MessageLogger::logError('ENVOI_MESSAGE', $message, $e, [
                'receiver_id' => $request->id_destinataire ?? 'inconnu',
                'has_content' => !empty(trim($request->contenu ?? '')),
                'has_files' => $request->hasFile('pieces_jointes'),
                'has_audio' => !empty($request->input('audio_data', '')),
                'has_video' => !empty($request->input('video_data', ''))
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valider la requête de message
     */
    private function validateMessageRequest(Request $request)
    {
        $rules = [
            'id_destinataire' => 'required|exists:users,id',
            'contenu' => 'nullable|string|max:10000',
            'id_annonce' => 'nullable|exists:annonce,id',
            'reponse_a_id' => 'nullable|exists:message,id',
            'pieces_jointes' => 'nullable|array|max:' . self::MAX_TOTAL_FILES,
            'pieces_jointes.*' => 'nullable|file|max:10240',
            'audio_data' => 'nullable|string',
            'video_data' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($request->hasFile('pieces_jointes')) {
            $files = $request->file('pieces_jointes');
            
            foreach ($files as $index => $file) {
                if (!$file->isValid()) {
                    $validator->errors()->add("pieces_jointes.{$index}", 
                        "Le fichier '{$file->getClientOriginalName()}' est invalide.");
                    continue;
                }
                
                $extension = strtolower($file->getClientOriginalExtension());
                $size = $file->getSize();
                $mimeType = $file->getMimeType();

                if (in_array($extension, self::ALLOWED_IMAGES) || strpos($mimeType, 'image/') === 0) {
                    if ($size > self::MAX_IMAGE_SIZE) {
                        $validator->errors()->add("pieces_jointes.{$index}", 
                            "L'image '{$file->getClientOriginalName()}' dépasse la limite de 5MB.");
                    }
                } elseif (in_array($extension, self::ALLOWED_VIDEOS) || strpos($mimeType, 'video/') === 0) {
                    if ($size > self::MAX_VIDEO_SIZE) {
                        $validator->errors()->add("pieces_jointes.{$index}", 
                            "La vidéo '{$file->getClientOriginalName()}' dépasse la limite de 10MB.");
                    }
                } elseif (in_array($extension, self::ALLOWED_AUDIOS) || strpos($mimeType, 'audio/') === 0) {
                    if ($size > self::MAX_AUDIO_SIZE) {
                        $validator->errors()->add("pieces_jointes.{$index}", 
                            "L'audio '{$file->getClientOriginalName()}' dépasse la limite de 10MB.");
                    }
                } elseif (in_array($extension, self::ALLOWED_DOCUMENTS)) {
                    if ($size > self::MAX_DOCUMENT_SIZE) {
                        $validator->errors()->add("pieces_jointes.{$index}", 
                            "Le document '{$file->getClientOriginalName()}' dépasse la limite de 5MB.");
                    }
                } else {
                    $validator->errors()->add("pieces_jointes.{$index}", 
                        "Le fichier '{$file->getClientOriginalName()}' n'est pas autorisé.");
                }
            }
        }

        return $validator;
    }

    /**
     * Stocker un fichier avec optimisation
     */
    private function storeFile($file, Message $message): bool
    {
        try {
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $size = $file->getSize();

            $typeMedia = $this->getMediaType($extension);
            $subDirectory = $this->getSubDirectory($typeMedia);
            $fileName = $this->generateUniqueFileName($originalName, $extension);
            $path = $subDirectory . '/' . $fileName;

            Storage::disk('public')->makeDirectory($subDirectory);

            if ($typeMedia === 'image') {
                $this->optimizeImage($file, $path);
            } else {
                $file->storeAs($subDirectory, $fileName, 'public');
            }

            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("Le fichier n'a pas été stocké correctement: {$path}");
            }

            $storedSize = Storage::disk('public')->size($path);

            MessagePieceJointe::create([
                'id_message' => $message->id,
                'type_media' => $typeMedia,
                'nom_media' => $originalName,
                'taille' => $size,
                'chemin_stockage' => $path,
                'statut' => 'actif',
            ]);

            MessageLogger::logSuccess('STOCKAGE_FICHIER', $message, [
                'files' => $originalName . " (" . $typeMedia . ", " . number_format($size / 1024, 1) . " KB)",
                'info' => "Fichier stocké dans: {$path}"
            ]);

            return true;
        } catch (Throwable $e) {
            MessageLogger::logError('STOCKAGE_FICHIER', $message, $e, [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);
            return false;
        }
    }

    /**
     * Optimiser une image avec Intervention Image - Version corrigée
     */
    private function optimizeImage($file, string $path): void
    {
        try {
            // Vérifier si l'extension GD est disponible
            if (!extension_loaded('gd')) {
                throw new \Exception('L\'extension GD n\'est pas chargée');
            }

            // Charger l'image avec GD directement pour éviter les problèmes de compatibilité
            $imageInfo = getimagesize($file->getRealPath());
            if (!$imageInfo) {
                throw new \Exception('Impossible de lire l\'image');
            }

            $mimeType = $imageInfo['mime'];
            $source = null;

            // Créer la ressource image selon le type MIME
            switch ($mimeType) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'image/png':
                    $source = imagecreatefrompng($file->getRealPath());
                    // Préserver la transparence pour PNG
                    imagealphablending($source, true);
                    imagesavealpha($source, true);
                    break;
                case 'image/gif':
                    $source = imagecreatefromgif($file->getRealPath());
                    break;
                case 'image/webp':
                    if (function_exists('imagecreatefromwebp')) {
                        $source = imagecreatefromwebp($file->getRealPath());
                    } else {
                        throw new \Exception('Le format WebP n\'est pas supporté par votre installation GD');
                    }
                    break;
                case 'image/bmp':
                case 'image/x-ms-bmp':
                    if (function_exists('imagecreatefrombmp')) {
                        $source = imagecreatefrombmp($file->getRealPath());
                    } else {
                        throw new \Exception('Le format BMP n\'est pas supporté par votre installation GD');
                    }
                    break;
                default:
                    throw new \Exception("Type MIME non supporté: {$mimeType}");
            }

            if (!$source) {
                throw new \Exception('Impossible de créer la ressource image');
            }

            $width = imagesx($source);
            $height = imagesy($source);

            // Redimensionner si nécessaire
            $maxWidth = 1920;
            $maxHeight = 1080;
            $newWidth = $width;
            $newHeight = $height;

            if ($width > $maxWidth || $height > $maxHeight) {
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = round($width * $ratio);
                $newHeight = round($height * $ratio);

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                
                // Préserver la transparence pour PNG
                if ($mimeType === 'image/png') {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                    imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
                }

                imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($source);
                $source = $resized;
                $width = $newWidth;
                $height = $newHeight;

                MessageLogger::logSuccess('OPTIMISATION_IMAGE', null, [
                    'info' => "Image redimensionnée de {$width}x{$height} à {$newWidth}x{$newHeight}"
                ]);
            }

            // Compression selon le type
            $extension = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
            $quality = 85;
            $output = null;

            // Créer un buffer de sortie
            ob_start();

            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($source, null, $quality);
                    break;
                case 'png':
                    // Pour PNG, qualité = niveau de compression (0-9)
                    $compression = 6;
                    imagepng($source, null, $compression);
                    break;
                case 'gif':
                    imagegif($source, null);
                    break;
                case 'webp':
                    if (function_exists('imagewebp')) {
                        imagewebp($source, null, $quality);
                    } else {
                        // Fallback vers JPEG si WebP n'est pas supporté
                        imagejpeg($source, null, $quality);
                    }
                    break;
                default:
                    imagejpeg($source, null, $quality);
                    break;
            }

            $imageData = ob_get_clean();
            imagedestroy($source);

            if (!$imageData) {
                throw new \Exception('Échec de l\'encodage de l\'image');
            }

            // Sauvegarder l'image
            Storage::disk('public')->put($path, $imageData);

            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("Échec de l'enregistrement de l'image optimisée: {$path}");
            }

            $newSize = Storage::disk('public')->size($path);
            $originalSize = $file->getSize();

            MessageLogger::logSuccess('OPTIMISATION_IMAGE', null, [
                'info' => "Image optimisée: " . number_format($newSize / 1024, 1) . " KB (original: " . number_format($originalSize / 1024, 1) . " KB)"
            ]);

        } catch (Throwable $e) {
            MessageLogger::logError('OPTIMISATION_IMAGE', null, $e, [
                'path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize()
            ]);
            
            // Fallback : sauvegarder l'image sans optimisation
            try {
                $content = file_get_contents($file->getRealPath());
                Storage::disk('public')->put($path, $content);
                
                MessageLogger::logSuccess('OPTIMISATION_IMAGE', null, [
                    'info' => "Image sauvegardée sans optimisation (fallback)"
                ]);
            } catch (Throwable $e2) {
                MessageLogger::logError('OPTIMISATION_IMAGE_FALLBACK', null, $e2, [
                    'path' => $path
                ]);
                throw $e2;
            }
        }
    }

    /**
     * Stocker un enregistrement audio au format OPUS
     */
    private function storeAudioOpus(string $audioData, Message $message): bool
    {
        try {
            if (strpos($audioData, 'base64,') !== false) {
                $parts = explode('base64,', $audioData);
                $audioData = $parts[1] ?? '';
            }
            
            $audioBinary = base64_decode($audioData);
            
            if (empty($audioBinary)) {
                throw new \Exception('Données audio invalides');
            }

            $size = strlen($audioBinary);
            if ($size > self::MAX_AUDIO_SIZE) {
                throw new \Exception('L\'audio dépasse la limite de 10MB');
            }

            $subDirectory = 'messages/audios';
            Storage::disk('public')->makeDirectory($subDirectory);
            
            $fileName = $this->generateUniqueFileName('Enregistrement audio', 'opus');
            $path = $subDirectory . '/' . $fileName;
            
            Storage::disk('public')->put($path, $audioBinary);

            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception('Impossible de sauvegarder le fichier audio');
            }

            MessagePieceJointe::create([
                'id_message' => $message->id,
                'type_media' => 'audio',
                'nom_media' => 'Enregistrement audio.opus',
                'taille' => $size,
                'chemin_stockage' => $path,
                'statut' => 'actif',
            ]);

            MessageLogger::logSuccess('ENREGISTREMENT_AUDIO', $message, [
                'info' => "Audio enregistré (" . number_format($size / 1024, 1) . " KB)"
            ]);

            return true;
        } catch (Throwable $e) {
            MessageLogger::logError('ENREGISTREMENT_AUDIO', $message, $e);
            return false;
        }
    }

    /**
     * Stocker un enregistrement vidéo
     */
    private function storeVideoRecording(string $videoData, Message $message): bool
    {
        try {
            if (strpos($videoData, 'base64,') !== false) {
                $parts = explode('base64,', $videoData);
                $videoData = $parts[1] ?? '';
            }
            
            $videoBinary = base64_decode($videoData);
            
            if (empty($videoBinary)) {
                throw new \Exception('Données vidéo invalides');
            }

            $size = strlen($videoBinary);
            if ($size > self::MAX_VIDEO_SIZE) {
                throw new \Exception('La vidéo dépasse la limite de 10MB');
            }

            $subDirectory = 'messages/videos';
            Storage::disk('public')->makeDirectory($subDirectory);
            
            $fileName = $this->generateUniqueFileName('Enregistrement vidéo', 'webm');
            $path = $subDirectory . '/' . $fileName;
            
            Storage::disk('public')->put($path, $videoBinary);

            MessagePieceJointe::create([
                'id_message' => $message->id,
                'type_media' => 'video',
                'nom_media' => 'Enregistrement vidéo.webm',
                'taille' => $size,
                'chemin_stockage' => $path,
                'statut' => 'actif',
            ]);

            MessageLogger::logSuccess('ENREGISTREMENT_VIDEO', $message, [
                'info' => "Vidéo enregistrée (" . number_format($size / 1024, 1) . " KB)"
            ]);

            return true;
        } catch (Throwable $e) {
            MessageLogger::logError('ENREGISTREMENT_VIDEO', $message, $e);
            return false;
        }
    }

    /**
     * Déterminer le sous-dossier en fonction du type de média
     */
    private function getSubDirectory(string $typeMedia): string
    {
        return match($typeMedia) {
            'image' => 'messages/images',
            'video' => 'messages/videos',
            'audio' => 'messages/audios',
            default => 'messages/documents',
        };
    }

    /**
     * Générer un nom de fichier unique
     */
    private function generateUniqueFileName(string $originalName, string $extension): string
    {
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', $baseName);
        $baseName = substr($baseName, 0, 30);
        
        $uniqueId = uniqid() . '_' . bin2hex(random_bytes(4));
        $timestamp = time();
        
        return $timestamp . '_' . $uniqueId . '_' . $baseName . '.' . $extension;
    }

    /**
     * Déterminer le type de média en fonction de l'extension
     */
    private function getMediaType(string $extension): string
    {
        $extension = strtolower($extension);
        
        if (in_array($extension, self::ALLOWED_IMAGES)) {
            return 'image';
        } elseif (in_array($extension, self::ALLOWED_VIDEOS)) {
            return 'video';
        } elseif (in_array($extension, self::ALLOWED_AUDIOS)) {
            return 'audio';
        }
        
        return 'document';
    }

    /**
     * Rendre un message en HTML pour AJAX
     */
    private function renderMessage(Message $message): string
    {
        $currentUserId = Auth::id();
        $isMine = $message->id_expediteur === $currentUserId;

        $html = '<div class="message-item flex ' . ($isMine ? 'justify-end' : 'justify-start') . '" data-message-id="' . $message->id . '" data-sender-id="' . $message->id_expediteur . '" data-sender-name="' . e($message->expediteur->prenom ?? '') . '">';
        $html .= '<div class="max-w-[80%] ' . ($isMine ? 'order-2' : 'order-1') . '">';
        
        $style = $isMine 
            ? 'background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); color: white;' 
            : 'background-color: var(--color-secondary-light); color: var(--color-nav-text);';
        
        $html .= '<div class="rounded-xl px-4 py-2.5 shadow-sm message-bubble" style="' . $style . '">';
        
        $html .= '<div class="message-content" data-message-id="' . $message->id . '">';
        if ($message->contenu) {
            $contenu = nl2br(e($message->contenu));
            $html .= '<p class="text-sm message-text" id="message-text-' . $message->id . '">' . $contenu . '</p>';
        }
        $html .= '</div>';
        
        if ($message->piecesJointes->count() > 0) {
            $html .= '<div class="mt-2 space-y-2">';
            foreach ($message->piecesJointes as $piece) {
                $fileExists = Storage::disk('public')->exists($piece->chemin_stockage);
                if ($fileExists) {
                    $html .= $this->renderPieceJointe($piece, $isMine);
                }
            }
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        $html .= '<div class="flex items-center gap-2 mt-1 ' . ($isMine ? 'justify-end' : 'justify-start') . '">';
        $html .= '<span class="text-[10px]" style="color: var(--color-nav-text); opacity: 0.5;">';
        $html .= $message->created_at->format('d/m/Y H:i');
        if ($isMine) {
            $html .= $message->lu ? '<i class="fas fa-check-double ml-1 text-blue-500"></i>' : '<i class="fas fa-check ml-1"></i>';
        }
        if ($message->has_pieces_jointes) {
            $html .= '<i class="fas fa-paperclip ml-1"></i>';
        }
        if ($message->created_at != $message->updated_at) {
            $html .= ' <span class="text-[8px] opacity-40 ml-1">(modifié)</span>';
        }
        $html .= '</span>';
        
        $senderName = $message->expediteur->prenom ?? 'Utilisateur';
        $html .= '<button onclick="replyToMessage(' . $message->id . ', \'' . addslashes($senderName) . '\')" class="text-[10px] transition hover:scale-110" style="color: var(--color-primary);" title="Répondre à ce message">';
        $html .= '<i class="fas fa-reply"></i>';
        $html .= '</button>';
        $html .= '</div>';
        
        $html .= '</div>';
        
        if (!$isMine) {
            $html .= '<div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0 ml-2 order-2" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">';
            $html .= strtoupper(substr($message->expediteur->prenom ?? '', 0, 1) . substr($message->expediteur->nom ?? '', 0, 1));
            $html .= '</div>';
        }
        
        $html .= '</div>';

        return $html;
    }

    /**
     * Rendre une pièce jointe en HTML
     */
    private function renderPieceJointe($piece, bool $isMine): string
    {
        $html = '';
        $url = asset('storage/' . $piece->chemin_stockage);
        
        if ($piece->type_media === 'image') {
            $html .= '<div class="rounded-lg overflow-hidden max-w-xs">';
            $html .= '<img src="' . $url . '" alt="' . e($piece->nom_media) . '" class="w-full h-auto max-h-64 object-cover cursor-pointer" style="display: block;" onclick="openImagePreview(\'' . $url . '\', \'' . e($piece->nom_media) . '\', \'' . route('messagerie.download-piece', $piece->id) . '\')" loading="lazy">';
            $html .= '<div class="flex items-center justify-between mt-1 px-1">';
            $html .= '<span class="text-[10px] opacity-60">' . e($piece->nom_media) . '</span>';
            $html .= '<span class="text-[10px] opacity-60">' . number_format($piece->taille / 1024, 1) . ' KB</span>';
            $html .= '</div></div>';
        } elseif ($piece->type_media === 'video') {
            $html .= '<div class="rounded-lg overflow-hidden max-w-xs">';
            $html .= '<video controls class="w-full max-h-64" preload="metadata" style="display: block; background: #000;">';
            $html .= '<source src="' . $url . '" type="video/mp4">';
            $html .= '<source src="' . $url . '" type="video/webm">';
            $html .= '<source src="' . $url . '" type="video/avi">';
            $html .= 'Votre navigateur ne supporte pas la lecture de vidéo.';
            $html .= '</video>';
            $html .= '<div class="flex items-center justify-between mt-1 px-1">';
            $html .= '<span class="text-[10px] opacity-60">' . e($piece->nom_media) . '</span>';
            $html .= '<span class="text-[10px] opacity-60">' . number_format($piece->taille / 1024, 1) . ' KB</span>';
            $html .= '</div></div>';
        } elseif ($piece->type_media === 'audio') {
            $bg = $isMine ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
            $html .= '<div class="rounded-lg p-2 max-w-xs" style="background: ' . $bg . ';">';
            $html .= '<audio controls class="w-full" preload="metadata">';
            $html .= '<source src="' . $url . '" type="audio/opus">';
            $html .= '<source src="' . $url . '" type="audio/webm">';
            $html .= '<source src="' . $url . '" type="audio/mpeg">';
            $html .= 'Votre navigateur ne supporte pas la lecture audio.';
            $html .= '</audio>';
            $html .= '<div class="flex items-center justify-between mt-1 px-1">';
            $html .= '<span class="text-[10px] opacity-60">' . e($piece->nom_media) . '</span>';
            $html .= '<span class="text-[10px] opacity-60">' . number_format($piece->taille / 1024, 1) . ' KB</span>';
            $html .= '</div></div>';
        } else {
            $bg = $isMine ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
            $color = $isMine ? 'white' : 'var(--color-primary)';
            $html .= '<div class="flex items-center gap-2 p-2 rounded-lg" style="background: ' . $bg . ';">';
            $html .= '<i class="fas fa-file text-lg" style="color: ' . $color . ';"></i>';
            $html .= '<a href="' . route('messagerie.download-piece', $piece->id) . '" target="_blank" class="text-sm hover:underline truncate flex-1" style="color: ' . $color . ';">';
            $html .= e($piece->nom_media);
            $html .= '</a>';
            $html .= '<span class="text-[10px] opacity-60 flex-shrink-0">' . number_format($piece->taille / 1024, 1) . ' KB</span>';
            $html .= '</div>';
        }
        
        return $html;
    }

    /**
     * Modifier un message (AJAX)
     */
    public function update(Request $request, $id)
    {
        try {
            $message = Message::where('id_expediteur', Auth::id())->findOrFail($id);

            $request->validate([
                'contenu' => 'required|string|max:10000',
            ]);

            $oldContent = $message->contenu;
            
            $message->update([
                'contenu' => trim($request->contenu),
                'updated_at' => now(),
            ]);

            MessageLogger::logSuccess('MODIFICATION_MESSAGE', $message, [
                'info' => "Ancien contenu: " . substr($oldContent, 0, 30) . (strlen($oldContent) > 30 ? '...' : '')
            ]);

            $newContent = nl2br(e($message->contenu));

            return response()->json([
                'success' => true,
                'message' => 'Message modifié avec succès !',
                'new_content' => $newContent,
                'updated_at' => $message->updated_at->format('d/m/Y H:i')
            ]);

        } catch (ValidationException $e) {
            MessageLogger::logError('MODIFICATION_MESSAGE', null, $e, [
                'message_id' => $id,
                'error' => 'Validation échouée'
            ]);
            throw $e;
        } catch (Throwable $e) {
            MessageLogger::logError('MODIFICATION_MESSAGE', null, $e, [
                'message_id' => $id
            ]);
            throw $e;
        }
    }

    /**
     * Supprimer un message (AJAX)
     */
    public function destroy($id)
    {
        try {
            $message = Message::where('id_expediteur', Auth::id())->findOrFail($id);

            $filesCount = $message->piecesJointes->count();
            $deletedFiles = [];

            // Supprimer les pièces jointes physiquement
            foreach ($message->piecesJointes as $piece) {
                $filePath = $piece->chemin_stockage;
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                    $deletedFiles[] = $piece->nom_media;
                }
                $piece->delete();
            }

            $message->delete();

            MessageLogger::logSuccess('SUPPRESSION_MESSAGE', $message, [
                'info' => "Message supprimé avec " . $filesCount . " pièce(s) jointe(s)" . (!empty($deletedFiles) ? ": " . implode(', ', $deletedFiles) : "")
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message supprimé avec succès.'
            ]);

        } catch (Throwable $e) {
            MessageLogger::logError('SUPPRESSION_MESSAGE', null, $e, [
                'message_id' => $id
            ]);
            throw $e;
        }
    }

    /**
     * Télécharger une pièce jointe (AJAX/Route)
     */
    public function downloadPiece($id)
    {
        try {
            $piece = MessagePieceJointe::with('message')->findOrFail($id);
            $message = $piece->message;
            
            if ($message->id_expediteur !== Auth::id() && $message->id_destinataire !== Auth::id()) {
                MessageLogger::logError('TELECHARGEMENT_FICHIER', $message, null, [
                    'error' => 'Accès non autorisé au fichier',
                    'piece_id' => $id
                ]);
                abort(403, 'Vous n\'êtes pas autorisé à télécharger ce fichier.');
            }
            
            $fileExists = Storage::disk('public')->exists($piece->chemin_stockage);
            
            if ($fileExists) {
                MessageLogger::logSuccess('TELECHARGEMENT_FICHIER', $message, [
                    'files' => $piece->nom_media,
                    'info' => "Fichier téléchargé (" . $piece->type_media . ", " . number_format($piece->taille / 1024, 1) . " KB)"
                ]);
                
                return Storage::disk('public')->download($piece->chemin_stockage, $piece->nom_media);
            }
            
            MessageLogger::logError('TELECHARGEMENT_FICHIER', $message, null, [
                'error' => "Fichier introuvable: {$piece->nom_media}",
                'piece_id' => $id,
                'path' => $piece->chemin_stockage
            ]);
            
            return redirect()->back()->with('error', 'Le fichier n\'existe plus.');
            
        } catch (Throwable $e) {
            MessageLogger::logError('TELECHARGEMENT_FICHIER', null, $e, [
                'piece_id' => $id
            ]);
            throw $e;
        }
    }

    /**
     * Démarrer une conversation depuis une annonce (AJAX/Route)
     */
    public function startFromAnnonce(Request $request)
    {
        try {
            $request->validate([
                'id_annonce' => 'required|exists:annonce,id',
                'contenu' => 'required|string|max:1000',
            ]);

            $annonce = Annonce::with('auteur')->findOrFail($request->id_annonce);
            $vendeurId = $annonce->id_user;

            if ($vendeurId === Auth::id()) {
                MessageLogger::logError('DEMARRAGE_CONVERSATION', null, null, [
                    'error' => 'Tentative d\'auto-envoi de message',
                    'annonce_id' => $request->id_annonce
                ]);
                return redirect()->back()->with('error', 'Vous ne pouvez pas vous envoyer un message à vous-même.');
            }

            $existingMessage = Message::where('id_expediteur', Auth::id())
                ->where('id_destinataire', $vendeurId)
                ->where('id_annonce', $request->id_annonce)
                ->first();

            if ($existingMessage) {
                MessageLogger::logSuccess('DEMARRAGE_CONVERSATION', $existingMessage, [
                    'info' => "Conversation existante avec l'annonce ID={$request->id_annonce}"
                ]);
                return redirect()->route('messagerie.show', $vendeurId)
                    ->with('info', 'Vous avez déjà une discussion concernant cette annonce.');
            }

            $message = Message::create([
                'id_expediteur' => Auth::id(),
                'id_destinataire' => $vendeurId,
                'id_annonce' => $request->id_annonce,
                'contenu' => trim($request->contenu),
                'date_envoi' => now(),
                'est_reponse' => false,
                'reponse_a_id' => null,
                'lu' => false,
                'has_pieces_jointes' => false,
            ]);

            MessageLogger::logSuccess('DEMARRAGE_CONVERSATION', $message, [
                'info' => "Nouvelle conversation démarrée via l'annonce ID={$request->id_annonce}"
            ]);

            return redirect()->route('messagerie.show', $vendeurId)
                ->with('success', 'Votre message a été envoyé au vendeur !');

        } catch (ValidationException $e) {
            MessageLogger::logError('DEMARRAGE_CONVERSATION', null, $e, [
                'annonce_id' => $request->id_annonce ?? null,
                'error' => 'Validation échouée'
            ]);
            throw $e;
        } catch (Throwable $e) {
            MessageLogger::logError('DEMARRAGE_CONVERSATION', null, $e, [
                'annonce_id' => $request->id_annonce ?? null
            ]);
            throw $e;
        }
    }

    /**
     * Marquer tous les messages comme lus (AJAX)
     */
    public function markAllAsRead($userId)
    {
        try {
            $count = Message::where('id_destinataire', Auth::id())
                ->where('id_expediteur', $userId)
                ->update(['lu' => true]);

            MessageLogger::logSuccess('MARQUER_COMME_LU', null, [
                'info' => "{$count} messages marqués comme lus de l'utilisateur ID={$userId}"
            ]);

            return redirect()->back()->with('success', 'Tous les messages ont été marqués comme lus.');

        } catch (Throwable $e) {
            MessageLogger::logError('MARQUER_COMME_LU', null, $e, [
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Compter les messages non lus (AJAX)
     */
    public function unreadCount()
    {
        try {
            $count = Message::where('id_destinataire', Auth::id())
                ->where('lu', false)
                ->count();

            MessageLogger::logSuccess('COMPTER_NON_LUS', null, [
                'info' => "{$count} message(s) non lu(s)"
            ]);

            return response()->json(['unread' => $count]);

        } catch (Throwable $e) {
            MessageLogger::logError('COMPTER_NON_LUS', null, $e);
            return response()->json(['unread' => 0, 'error' => 'Erreur de calcul'], 500);
        }
    }
}