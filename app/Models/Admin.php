<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'id_user',
        'type_admin',
        'description',
        'statut'
    ];

    protected $casts = [
        'type_admin' => 'string',
        'statut' => 'string',
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Accesseur pour vérifier si c'est un super admin
    public function isSuperAdmin(): bool
    {
        return $this->type_admin === 'super_admin';
    }

    // Accesseur pour vérifier si l'admin est actif
    public function isActive(): bool
    {
        return $this->statut === 'actif';
    }

    // Scope pour les super admins
    public function scopeSuperAdmins($query)
    {
        return $query->where('type_admin', 'super_admin');
    }

    // Scope pour les admins actifs
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }
}