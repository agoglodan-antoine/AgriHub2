<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = [
        'id_acheteur',
        'id_vendeur',
        'id_annonce',
        'id_transporteur',
        'montant_total',
        'commission_prelevee',
        'date_transaction',
        'statut_transaction',
        'avis_client',
        'note'
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'commission_prelevee' => 'decimal:2',
        'date_transaction' => 'datetime',
        'note' => 'integer',
        'statut_transaction' => 'string',
    ];

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

    public function lignes()
    {
        return $this->hasMany(LigneTransaction::class, 'id_transaction');
    }

    public function pointsFidelite()
    {
        return $this->hasMany(PointFidelite::class, 'id_transaction_source');
    }

    // Vérifier si la transaction est validée
    public function isValidee()
    {
        return $this->statut_transaction === 'validee';
    }
}