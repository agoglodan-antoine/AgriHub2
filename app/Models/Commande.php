<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commande';

    protected $fillable = [
        'id_acheteur',
        'id_vendeur',
        'id_annonce',
        'id_transporteur',
        'prix_unitaire',
        'quantite',
        'reduction',
        'montant_total',
        'montant_ajuste',
        'commission_prelevee',
        'date_commande',
        'statut_commande',
        'avis_client',
        'note'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'quantite' => 'decimal:2',
        'reduction' => 'decimal:2',
        'montant_total' => 'decimal:2',
        'montant_ajuste' => 'decimal:2',
        'commission_prelevee' => 'decimal:2',
        'date_commande' => 'datetime',
        'note' => 'integer',
        'statut_commande' => 'string',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    public function acheteur()
    {
        return $this->belongsTo(User::class, 'id_acheteur');
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'id_vendeur');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce');
    }

    public function transporteur()
    {
        return $this->belongsTo(User::class, 'id_transporteur');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_commande');
    }

    public function pointsFidelite()
    {
        return $this->hasMany(PointFidelite::class, 'id_commande');
    }

    // ============================================
    // MÉTHODES
    // ============================================

    public function isValidee()
    {
        return $this->statut_commande === 'validee';
    }

    public function isLivree()
    {
        return $this->statut_commande === 'livree';
    }

    public function isAnnulee()
    {
        return $this->statut_commande === 'annulee';
    }

    public function getReductionEnPourcentageAttribute()
    {
        if ($this->montant_total > 0) {
            return round(($this->reduction / $this->montant_total) * 100, 2);
        }
        return 0;
    }
}