<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneTransaction extends Model
{
    use HasFactory;

    protected $table = 'ligne_transaction';

    protected $fillable = [
        'id_transaction',
        'id_annonce',
        'quantite',
        'prix_unitaire',
        'montant_ligne'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_ligne' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce');
    }
}