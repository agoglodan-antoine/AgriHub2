<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilisateurRecompense extends Model
{
    use HasFactory;

    protected $table = 'utilisateur_recompense';

    protected $fillable = [
        'id_user',
        'id_recompense',
        'date_obtention',
        'statut'
    ];

    protected $casts = [
        'date_obtention' => 'datetime',
        'statut' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function recompense()
    {
        return $this->belongsTo(Recompense::class, 'id_recompense');
    }
}