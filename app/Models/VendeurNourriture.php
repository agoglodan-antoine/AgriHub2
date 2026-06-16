<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendeurNourriture extends Model
{
    use HasFactory;

    protected $table = 'vendeur_nourriture';

    protected $fillable = [
        'id_user',
        'nom_entreprise',
        'description',
        'localisation_gps'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function nourritures()
    {
        return $this->hasMany(Nourriture::class, 'id_user');
    }
}