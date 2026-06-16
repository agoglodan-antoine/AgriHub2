<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessagePieceJointe extends Model
{
    use HasFactory;

    protected $table = 'message_piece_jointes';

    protected $fillable = [
        'id_message',
        'type_media',
        'nom_media',
        'taille',
        'chemin_stockage',
        'statut'
    ];

    protected $casts = [
        'taille' => 'integer',
        'type_media' => 'string',
        'statut' => 'string',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class, 'id_message');
    }

    // Accesseur pour l'URL complète du média
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->chemin_stockage);
    }
}