<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domaine extends Model
{
    use HasFactory;

    protected $table = 'domaine';

    protected $fillable = [
        'nom',
        'label',
        'icone',
        'description',
        'statut'
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    public function especes()
    {
        return $this->hasMany(Espece::class, 'id_domaine');
    }
}