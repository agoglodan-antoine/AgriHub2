<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nourriture extends Model
{
    use HasFactory;

    protected $table = 'nourriture';

    protected $fillable = [
        'id_user',
        'type',
        'nom',
        'description'
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function especes()
    {
        return $this->belongsToMany(Espece::class, 'nourriture_espece', 'id_nourriture', 'id_espece')
                    ->withTimestamps();
    }

    public function annonce()
    {
        return $this->hasOne(Annonce::class, 'id_nourriture');
    }
}