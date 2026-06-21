<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Throwable;

class SseController extends Controller
{
    public function streamMessages(Request $request)
    {
        $userId = Auth::id();
        $otherUserId = $request->query('other_user_id');
        
        if (!$userId || !$otherUserId) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }
        
        // Headers pour SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
        
        // Dernier ID de message connu
        $lastMessageId = $request->query('last_message_id', 0);
        
        // Temps de la dernière vérification
        $lastCheck = time();
        $maxExecutionTime = 30; // Réduit à 30 secondes pour éviter les timeouts
        
        // Nombre de vérifications sans message
        $emptyChecks = 0;
        
        while (true) {
            // Vérifier si la connexion est toujours active
            if (connection_aborted()) {
                break;
            }
            
            // Vérifier le temps d'exécution
            if (time() - $lastCheck > $maxExecutionTime) {
                // Envoyer un heartbeat pour maintenir la connexion
                echo "event: heartbeat\n";
                echo "data: " . json_encode(['time' => time()]) . "\n\n";
                ob_flush();
                flush();
                $lastCheck = time();
                $emptyChecks = 0;
                continue;
            }
            
            // Vérifier les nouveaux messages
            try {
                $newMessages = Message::conversation($userId, $otherUserId)
                    ->where('id', '>', $lastMessageId)
                    ->where('id_expediteur', '!=', $userId)
                    ->with([
                        'expediteur',
                        'destinataire',
                        'annonce' => function($query) {
                            $query->with([
                                'piecesJointes',
                                'auteur',
                                'animal' => function($q) {
                                    $q->with('race');
                                },
                                'nourriture',
                                'accessoire',
                                'escrement'
                            ]);
                        },
                        'commande' => function($query) {
                            $query->with([
                                'acheteur',
                                'vendeur',
                                'annonce' => function($q) {
                                    $q->with([
                                        'piecesJointes',
                                        'auteur',
                                        'animal.race',
                                        'nourriture',
                                        'accessoire',
                                        'escrement'
                                    ]);
                                }
                            ]);
                        },
                        'piecesJointes'
                    ])
                    ->orderBy('created_at', 'asc')
                    ->get();
                
                if ($newMessages->count() > 0) {
                    // Marquer comme lus
                    Message::conversation($userId, $otherUserId)
                        ->whereIn('id', $newMessages->pluck('id'))
                        ->update(['lu' => true]);
                    
                    // Rendre chaque message
                    $html = '';
                    $messageIds = [];
                    
                    // Créer une instance du contrôleur avec l'utilisateur actuel
                    $messageController = new MessageController();
                    
                    foreach ($newMessages as $message) {
                        // Forcer l'utilisateur authentifié pour le rendu
                        $html .= $messageController->renderMessageForUser($message, $userId);
                        $messageIds[] = $message->id;
                    }
                    
                    $lastMessageId = end($messageIds);
                    
                    // Envoyer l'événement
                    echo "event: new_message\n";
                    echo "data: " . json_encode([
                        'html' => $html,
                        'count' => $newMessages->count(),
                        'last_message_id' => $lastMessageId
                    ]) . "\n\n";
                    ob_flush();
                    flush();
                    
                    // Mettre à jour le compteur
                    $unreadCount = Message::where('id_destinataire', $userId)
                        ->where('lu', false)
                        ->count();
                    
                    echo "event: unread_count\n";
                    echo "data: " . json_encode(['count' => $unreadCount]) . "\n\n";
                    ob_flush();
                    flush();
                    
                    $emptyChecks = 0;
                } else {
                    $emptyChecks++;
                    
                    // Si plusieurs vérifications sans message, envoyer un ping plus fréquent
                    if ($emptyChecks >= 5) {
                        echo "event: ping\n";
                        echo "data: " . json_encode(['time' => time()]) . "\n\n";
                        ob_flush();
                        flush();
                        $emptyChecks = 0;
                    }
                }
            } catch (Throwable $e) {
                \Log::error('Erreur SSE:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Envoyer une erreur mais continuer
                echo "event: error\n";
                echo "data: " . json_encode(['message' => $e->getMessage()]) . "\n\n";
                ob_flush();
                flush();
            }
            
            // Pause avant la prochaine vérification (2 secondes)
            sleep(2);
        }
    }
}