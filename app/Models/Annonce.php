<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonce';

    protected $fillable = [
        'id_user',
        'titre',
        'description',
        'type',
        'id_animal',
        'id_escrement',
        'id_nourriture',
        'id_accessoire',
        'quantite',
        'prix',
        'statut'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix' => 'decimal:2',
        'statut' => 'string',
        'type' => 'string',
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }

    public function escrement()
    {
        return $this->belongsTo(Escrement::class, 'id_escrement');
    }

    public function nourriture()
    {
        return $this->belongsTo(Nourriture::class, 'id_nourriture');
    }

    public function accessoire()
    {
        return $this->belongsTo(Accessoire::class, 'id_accessoire');
    }

    public function piecesJointes()
    {
        return $this->hasMany(AnnoncePieceJointe::class, 'id_annonce');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_annonce');
    }

    // Vérifier si l'annonce est active
    public function isActive()
    {
        return $this->statut === 'active';
    }
}