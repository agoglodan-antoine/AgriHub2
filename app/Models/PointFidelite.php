<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointFidelite extends Model
{
    use HasFactory;

    protected $table = 'point_fidelite';

    protected $fillable = [
        'id_user',
        'montant_points',
        'type_operation',
        'id_commande',
        'date_expiration'
    ];

    protected $casts = [
        'montant_points' => 'decimal:2',
        'type_operation' => 'string',
        'date_operation' => 'datetime',
        'date_expiration' => 'date',
    ];

    // ============================================
    // RELATIONS
    // ============================================

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }

    // ============================================
    // MÉTHODES
    // ============================================

    public function isExpired()
    {
        return $this->date_expiration && $this->date_expiration < now();
    }

    public function isGain()
    {
        return $this->type_operation === 'gain';
    }

    public function isDepense()
    {
        return $this->type_operation === 'depense';
    }
}