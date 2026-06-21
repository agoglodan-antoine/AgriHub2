<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
//use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $conversationId;
    public $newContent;
    public $updatedAt;

    public function __construct($messageId, $conversationId, $newContent, $updatedAt)
    {
        $this->messageId = $messageId;
        $this->conversationId = $conversationId;
        $this->newContent = $newContent;
        $this->updatedAt = $updatedAt;
    }

    public function broadcastOn()
    {
        return new Channel('conversation.' . $this->conversationId);
    }

    // ✅ Force le nom court "MessageUpdatedEvent" pour matcher
    // channel.listen('.MessageUpdatedEvent', ...) côté Echo.
    public function broadcastAs()
    {
        return 'MessageUpdatedEvent';
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->messageId,
            'new_content' => nl2br(e($this->newContent)),
            'updated_at' => $this->updatedAt
        ];
    }
}