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
        'id_commande',
        'contenu',
        'date_envoi',
        'est_reponse',
        'reponse_a_id',
        'lu',
        'has_pieces_jointes',
        'est_demande_commande',
        'est_demande_paiement'
    ];

    protected $casts = [
        'date_envoi' => 'datetime',
        'est_reponse' => 'boolean',
        'lu' => 'boolean',
        'has_pieces_jointes' => 'boolean',
        'est_demande_commande' => 'boolean',
        'est_demande_paiement' => 'boolean',
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

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
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

    public function isDemandeCommande()
    {
        return $this->est_demande_commande === true;
    }

    public function isDemandePaiement()
    {
        return $this->est_demande_paiement === true;
    }

    public function hasAnnonce()
    {
        return $this->id_annonce !== null;
    }

    public function hasCommande()
    {
        return $this->id_commande !== null;
    }

    // Créer un message de demande de commande
    public static function createDemandeCommande($expediteurId, $destinataireId, $annonceId)
    {
        return self::create([
            'id_expediteur' => $expediteurId,
            'id_destinataire' => $destinataireId,
            'id_annonce' => $annonceId,
            'contenu' => 'AgriHub assure la sécurité de vos commandes.',
            'est_demande_commande' => true,
            'date_envoi' => now(),
        ]);
    }

    // Créer un message de demande de paiement
    public static function createDemandePaiement($expediteurId, $destinataireId, $commandeId)
    {
        $commande = Commande::find($commandeId);
        
        return self::create([
            'id_expediteur' => $expediteurId,
            'id_destinataire' => $destinataireId,
            'id_annonce' => $commande->id_annonce,
            'id_commande' => $commandeId,
            'contenu' => 'Payer toujours par AgriHub pour votre sécurité.',
            'est_demande_paiement' => true,
            'date_envoi' => now(),
        ]);
    }
}