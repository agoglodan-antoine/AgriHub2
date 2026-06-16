<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporteur extends Model
{
    use HasFactory;

    protected $table = 'transporteur';

    protected $fillable = [
        'id_user',
        'type_vehicule',
        'capacite_transport',
        'zone_intervention',
        'licence_transport'
    ];

    protected $casts = [
        'capacite_transport' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_transporteur');
    }
}