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
        'id_transaction_source',
        'date_expiration'
    ];

    protected $casts = [
        'montant_points' => 'decimal:2',
        'type_operation' => 'string',
        'date_operation' => 'datetime',
        'date_expiration' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction_source');
    }

    // Vérifier si les points sont expirés
    public function isExpired()
    {
        return $this->date_expiration && $this->date_expiration < now();
    }
}