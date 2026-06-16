<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zoologie extends Model
{
    use HasFactory;

    protected $table = 'zoologie';

    protected $fillable = [
        'nom',
        'label',
        'description',
        'statut'
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    public function especes()
    {
        return $this->hasMany(Espece::class, 'id_zoologie');
    }
}