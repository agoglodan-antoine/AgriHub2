<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NourritureEspece extends Model
{
    use HasFactory;

    protected $table = 'nourriture_espece';

    protected $fillable = [
        'id_nourriture',
        'id_espece'
    ];

    public function nourriture()
    {
        return $this->belongsTo(Nourriture::class, 'id_nourriture');
    }

    public function espece()
    {
        return $this->belongsTo(Espece::class, 'id_espece');
    }
}