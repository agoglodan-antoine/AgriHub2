<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recompense extends Model
{
    use HasFactory;

    protected $table = 'recompense';

    protected $fillable = [
        'nom_recompense',
        'description',
        'cout_points',
        'type_recompense'
    ];

    protected $casts = [
        'cout_points' => 'decimal:2',
    ];

    public function utilisateurs()
    {
        return $this->belongsToMany(User::class, 'utilisateur_recompense', 'id_recompense', 'id_user')
                    ->withPivot('date_obtention', 'statut')
                    ->withTimestamps();
    }
}