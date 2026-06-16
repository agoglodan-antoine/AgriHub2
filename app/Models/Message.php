<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'message';

    protected $fillable = [
        'id_expediteur',
        'id_destinataire',
        'id_annonce',
        'contenu',
        'date_envoi',
        'est_reponse',
        'reponse_a_id',
        'lu',
        'has_pieces_jointes'
    ];

    protected $casts = [
        'date_envoi' => 'datetime',
        'est_reponse' => 'boolean',
        'lu' => 'boolean',
        'has_pieces_jointes' => 'boolean',
    ];

    // Relations
    public function expediteur()
    {
        return $this->belongsTo(User::class, 'id_expediteur');
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'id_destinataire');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce');
    }

    public function reponseA()
    {
        return $this->belongsTo(Message::class, 'reponse_a_id');
    }

    public function reponses()
    {
        return $this->hasMany(Message::class, 'reponse_a_id');
    }

    public function piecesJointes()
    {
        return $this->hasMany(MessagePieceJointe::class, 'id_message');
    }

    // Scopes
    public function scopeConversation($query, $userId1, $userId2)
    {
        return $query->where(function($q) use ($userId1, $userId2) {
            $q->where('id_expediteur', $userId1)
              ->where('id_destinataire', $userId2);
        })->orWhere(function($q) use ($userId1, $userId2) {
            $q->where('id_expediteur', $userId2)
              ->where('id_destinataire', $userId1);
        });
    }

    public function scopeUnread($query, $userId)
    {
        return $query->where('id_destinataire', $userId)->where('lu', false);
    }

    // Méthodes
    public function markAsRead()
    {
        $this->update(['lu' => true]);
    }

    public function isFromUser($userId)
    {
        return $this->id_expediteur === $userId;
    }

    public function getOtherUser($userId)
    {
        return $this->id_expediteur === $userId ? $this->id_destinataire : $this->id_expediteur;
    }
}