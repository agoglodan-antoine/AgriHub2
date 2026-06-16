<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veterinaire extends Model
{
    use HasFactory;

    protected $table = 'veterinaire';

    protected $fillable = [
        'id_user',
        'numero_ordre',
        'specialites',
        'zone_intervention'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class, 'id_veterinaire');
    }
}