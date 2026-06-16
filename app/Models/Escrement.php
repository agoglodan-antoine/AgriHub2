<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escrement extends Model
{
    use HasFactory;

    protected $table = 'escrement';

    protected $fillable = [
        'id_user',
        'id_espece',
        'nom',
        'description'
    ];

    public function producteur()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function espece()
    {
        return $this->belongsTo(Espece::class, 'id_espece');
    }

    public function annonce()
    {
        return $this->hasOne(Annonce::class, 'id_escrement');
    }
}