<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Parametre extends Model
{
    protected $table = 'parametres';
    
    protected $fillable = [
        'nom_plateforme',
        'logo',
        'slogan',
        'mail',
        'tel',
        'bp',
        'departement',
        'commune',
        'arrondissement',
        'facebook',
        'whatsapp',
        'linkedin',
        'twitter',
        'instagram',
        'description',
        'photo_de_vue'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Cache system
    public static function getCached()
    {
        return Cache::remember('site_settings', 3600, function () {
            return self::first();
        });
    }
    
    // Flush cache on update
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('site_settings');
        });
    }
}