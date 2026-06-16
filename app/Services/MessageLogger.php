<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageLogger
{
    /**
     * Journaliser une action sur un message (format simplifié)
     */
    public static function log(
        string $action,
        ?Message $message = null,
        ?User $user = null,
        ?array $data = [],
        bool $success = true,
        ?\Throwable $exception = null
    ): void {
        $user = $user ?? Auth::user();
        $userId = $user->id ?? 'GUEST';
        $userName = ($user->prenom ?? '') . ' ' . ($user->nom ?? '');
        $userName = trim($userName) ?: 'Utilisateur inconnu';

        $logLines = [];
        $logLines[] = "[" . now()->format('Y-m-d H:i:s') . "] ACTION: " . strtoupper($action);
        $logLines[] = "  👤 EXPEDITEUR: ID={$userId} | NOM={$userName}";

        if ($message) {
            $logLines[] = "  💬 MESSAGE: ID={$message->id} | DESTINATAIRE={$message->id_destinataire}";
            if ($message->id_annonce) {
                $logLines[] = "  📦 ANNONCE: ID={$message->id_annonce}";
            }
            if ($message->contenu) {
                $preview = strlen($message->contenu) > 50 ? substr($message->contenu, 0, 50) . '...' : $message->contenu;
                $logLines[] = "  📝 CONTENU: " . $preview;
            }
        }

        // Pièces jointes
        if ($message && $message->piecesJointes && $message->piecesJointes->count() > 0) {
            $files = [];
            foreach ($message->piecesJointes as $piece) {
                $files[] = $piece->nom_media . " ({$piece->type_media}, " . number_format($piece->taille / 1024, 1) . " KB)";
            }
            $logLines[] = "  📎 FICHIERS: " . implode(', ', $files);
        } elseif (!empty($data['files'])) {
            $logLines[] = "  📎 FICHIERS: " . $data['files'];
        }

        // Données audio/vidéo
        if (!empty($data['has_audio'])) {
            $logLines[] = "  🎤 AUDIO: Oui";
        }
        if (!empty($data['has_video'])) {
            $logLines[] = "  🎥 VIDEO: Oui";
        }

        // Statut
        if ($success) {
            $logLines[] = "  ✅ STATUT: SUCCES";
        } else {
            $logLines[] = "  ❌ STATUT: ECHEC";
            if ($exception) {
                $logLines[] = "  ⚠️  RAISON: " . $exception->getMessage();
            } elseif (!empty($data['error'])) {
                $logLines[] = "  ⚠️  RAISON: " . $data['error'];
            }
        }

        // Informations supplémentaires
        if (!empty($data['info'])) {
            $logLines[] = "  ℹ️ INFO: " . $data['info'];
        }

        // IP et User Agent (résumé)
        $logLines[] = "  🌐 IP: " . (request()->ip() ?? 'unknown');

        // Ajouter une séparation
        $logLines[] = "--------------------------------------------------------------------------------";

        // Écrire dans le fichier de log
        $logContent = implode("\n", $logLines) . "\n";

        // Déterminer le fichier de destination
        $logFile = $success ? 'message_audit.log' : 'message_errors.log';
        if ($action === 'error') {
            $logFile = 'message_errors.log';
        }

        try {
            $logPath = storage_path('logs/' . $logFile);
            file_put_contents($logPath, $logContent, FILE_APPEND | LOCK_EX);
        } catch (\Exception $e) {
            Log::error('Impossible d\'écrire dans le fichier de log: ' . $e->getMessage());
        }
    }

    /**
     * Journaliser une erreur simplifiée
     */
    public static function logError(
        string $action,
        ?Message $message = null,
        ?\Throwable $exception = null,
        ?array $context = []
    ): void {
        $data = $context ?? [];
        if ($exception) {
            $data['error'] = $exception->getMessage();
        }
        self::log($action, $message, null, $data, false, $exception);
    }

    /**
     * Journaliser une action réussie
     */
    public static function logSuccess(
        string $action,
        ?Message $message = null,
        ?array $data = []
    ): void {
        self::log($action, $message, null, $data, true, null);
    }
}