<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    protected $table = 'races';

    protected $fillable = [
        'nom',
        'label',
        'id_espece',
        'description',
        'statut'
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    public function espece()
    {
        return $this->belongsTo(Espece::class, 'id_espece');
    }

    public function animaux()
    {
        return $this->hasMany(Animal::class, 'id_race');
    }
}