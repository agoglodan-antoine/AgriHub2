<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnoncePieceJointe extends Model
{
    use HasFactory;

    protected $table = 'annonce_piece_jointe';

    protected $fillable = [
        'id_annonce',
        'type_media',
        'nom_media',
        'taille',
        'chemin_stockage',
        'est_principale',
        'ordre_affichage',
        'statut'
    ];

    protected $casts = [
        'taille' => 'integer',
        'est_principale' => 'boolean',
        'ordre_affichage' => 'integer',
        'type_media' => 'string',
        'statut' => 'string',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce');
    }

    // Accesseur pour l'URL complète du média
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->chemin_stockage);
    }
}