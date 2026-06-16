<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'id_veterinaire',
        'id_client',
        'sujet',
        'description',
        'date_prevue',
        'statut',
        'avis_client',
        'note'
    ];

    protected $casts = [
        'date_prevue' => 'datetime',
        'statut' => 'string',
        'note' => 'integer',
    ];

    public function veterinaire()
    {
        return $this->belongsTo(User::class, 'id_veterinaire');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'id_client');
    }

    // Vérifier si le rendez-vous est confirmé
    public function isConfirme()
    {
        return $this->statut === 'confirme';
    }
}