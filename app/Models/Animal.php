<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animaux';

    protected $fillable = [
        'id_user',
        'nom',
        'age_mois',
        'sexe',
        'id_race',
        'description',
        'statut'
    ];

    protected $casts = [
        'age_mois' => 'integer',
        'sexe' => 'string',
        'statut' => 'string',
    ];

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function race()
    {
        return $this->belongsTo(Race::class, 'id_race');
    }

    public function annonce()
    {
        return $this->hasOne(Annonce::class, 'id_animal');
    }

    // Accesseur pour l'âge en années
    public function getAgeAnneeAttribute()
    {
        return round($this->age_mois / 12, 1);
    }
}