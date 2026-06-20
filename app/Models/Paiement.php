<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiement';

    protected $fillable = [
        'id_commande',
        'montant_paye',
        'statut_paiement'
    ];

    protected $casts = [
        'montant_paye' => 'decimal:2',
        'statut_paiement' => 'string',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }

    // ============================================
    // MÉTHODES
    // ============================================

    public function isEnAttente()
    {
        return $this->statut_paiement === 'en_attente';
    }

    public function isReussi()
    {
        return $this->statut_paiement === 'reussi';
    }

    public function isEchoue()
    {
        return $this->statut_paiement === 'echoue';
    }

    public function isRembourse()
    {
        return $this->statut_paiement === 'rembourse';
    }
}