<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acheteur extends Model
{
    use HasFactory;

    protected $table = 'acheteur';

    protected $fillable = [
        'id_user',
        'type_acheteur',
        'preferences_achat'
    ];

    protected $casts = [
        'type_acheteur' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, User::class, 'id_user', 'id_acheteur');
    }
}