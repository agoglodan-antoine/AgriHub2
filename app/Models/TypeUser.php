<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUser extends Model
{
    use HasFactory;

    protected $table = 'type_user';

    protected $fillable = [
        'type',
        'label',
        'description',
        'statut'
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_type_user');
    }
}