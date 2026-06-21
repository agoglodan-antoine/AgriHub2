<?php

namespace App\Events;

use App\Models\Message;
use App\Http\Controllers\MessageController;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
//use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class NewMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $conversationId;

    public function __construct(Message $message, $conversationId)
    {
        $this->message = $message->load([
            'expediteur',
            'destinataire',
            'piecesJointes',
            'annonce',
            'annonce.piecesJointes',
            'annonce.auteur',
            'annonce.animal',
            'annonce.animal.race',
            'annonce.nourriture',
            'annonce.accessoire',
            'annonce.escrement',
            'commande'
        ]);
        $this->conversationId = $conversationId;
        
        Log::info('📨 NewMessageEvent créé', [
            'message_id' => $message->id,
            'conversation_id' => $conversationId,
            'expediteur' => $message->id_expediteur,
            'destinataire' => $message->id_destinataire
        ]);
    }

    public function broadcastOn()
    {
        Log::info('📡 Diffusion sur canal', [
            'channel' => 'conversation.' . $this->conversationId
        ]);
        return new Channel('conversation.' . $this->conversationId);
    }

    // ✅ Force le nom court "NewMessageEvent" au lieu du FQCN
    // "App\Events\NewMessageEvent" envoyé par défaut. Doit correspondre
    // exactement à channel.listen('.NewMessageEvent', ...) côté Echo.
    public function broadcastAs()
    {
        return 'NewMessageEvent';
    }

    public function broadcastWith()
    {
        $html = $this->renderMessageHtml();
        
        Log::info('📤 Contenu diffusé', [
            'message_id' => $this->message->id,
            'html_length' => strlen($html)
        ]);
        
        return [
            'message_id' => $this->message->id,
            'conversation_id' => $this->conversationId,
            'html' => $html,
            'sender_id' => $this->message->id_expediteur,
            'created_at' => $this->message->created_at->format('d/m/Y H:i')
        ];
    }

    private function renderMessageHtml()
    {
        try {
            // ✅ CORRECTION: Utiliser le conteneur d'application
            $controller = App::make(MessageController::class);
            // ✅ IMPORTANT: on rend le HTML du point de vue du DESTINATAIRE
            // (id_destinataire), pas de Auth::id() qui, dans ce contexte
            // synchrone (ShouldBroadcastNow), correspond toujours à
            // l'EXPÉDITEUR. Sans ça, le destinataire recevait un message
            // figé en "isMine = true" (aligné à droite comme s'il l'avait
            // envoyé lui-même), corrigé seulement après un refresh.
            return $controller->renderMessage($this->message, $this->message->id_destinataire);
        } catch (\Exception $e) {
            Log::error('❌ Erreur renderMessageHtml:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback: rendre un message simple
            // ✅ Cette HTML n'est diffusée qu'au destinataire (via toOthers()),
            // qui par définition n'est jamais l'expéditeur du message.
            $isMine = false;
            $html = '<div class="message-item flex ' . ($isMine ? 'justify-end' : 'justify-start') . '" data-message-id="' . $this->message->id . '" data-sender-id="' . $this->message->id_expediteur . '">';
            $html .= '<div class="max-w-[85%] ' . ($isMine ? 'order-2' : 'order-1') . '">';
            $html .= '<div class="rounded-xl px-4 py-2.5 shadow-sm" style="' . ($isMine ? 'background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); color: white;' : 'background-color: var(--color-secondary-light); color: var(--color-nav-text);') . '">';
            $html .= '<p class="text-sm">' . nl2br(e($this->message->contenu ?? 'Message')) . '</p>';
            $html .= '</div>';
            $html .= '<div class="flex items-center gap-2 mt-1 ' . ($isMine ? 'justify-end' : 'justify-start') . '">';
            $html .= '<span class="text-[10px]" style="color: var(--color-nav-text); opacity: 0.5;">' . $this->message->created_at->format('d/m/Y H:i') . '</span>';
            $html .= '</div></div></div>';
            
            return $html;
        }
    }
}