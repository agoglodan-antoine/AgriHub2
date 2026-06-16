<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espece extends Model
{
    use HasFactory;

    protected $table = 'especes';

    protected $fillable = [
        'nom',
        'label',
        'icone',
        'id_zoologie',
        'id_domaine',
        'description',
        'statut'
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    public function zoologie()
    {
        return $this->belongsTo(Zoologie::class, 'id_zoologie');
    }

    public function domaine()
    {
        return $this->belongsTo(Domaine::class, 'id_domaine');
    }

    public function races()
    {
        return $this->hasMany(Race::class, 'id_espece');
    }

    public function nourritures()
    {
        return $this->belongsToMany(Nourriture::class, 'nourriture_espece', 'id_espece', 'id_nourriture')
                    ->withTimestamps();
    }

    public function escrements()
    {
        return $this->hasMany(Escrement::class, 'id_espece');
    }
}