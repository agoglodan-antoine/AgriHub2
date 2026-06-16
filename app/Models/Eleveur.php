<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleveur extends Model
{
    use HasFactory;

    protected $table = 'eleveur';

    protected $fillable = [
        'id_user',
        'nom_elevage',
        'description_elevage',
        'localisation_gps',
        'siret'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function animaux()
    {
        return $this->hasManyThrough(Animal::class, User::class, 'id_user', 'id_user');
    }

    public function escrements()
    {
        return $this->hasManyThrough(Escrement::class, User::class, 'id_user', 'id_user');
    }
}