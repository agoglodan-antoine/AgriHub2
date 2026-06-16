<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendeurAccessoire extends Model
{
    use HasFactory;

    protected $table = 'vendeur_accessoire';

    protected $fillable = [
        'id_user',
        'nom_entreprise',
        'description',
        'localisation_gps'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function accessoires()
    {
        return $this->hasMany(Accessoire::class, 'id_user');
    }
}