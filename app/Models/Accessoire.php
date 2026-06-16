<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessoire extends Model
{
    use HasFactory;

    protected $table = 'accessoire';

    protected $fillable = [
        'id_user',
        'nom',
        'categorie',
        'description'
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function annonce()
    {
        return $this->hasOne(Annonce::class, 'id_accessoire');
    }
}