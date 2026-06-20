<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Parametre;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Récupérer les paramètres une seule fois pour les mettre en cache
        $settings = Parametre::first() ?? null;
        
        if (!$settings) {
            $settings = (object)[
                'nom_plateforme' => 'AgriHub Bénin',
                'logo' => 'logo/agrihub.png',
                'slogan' => "L'harmonie entre les acteurs du secteur élevage au Bénin",
                'mail' => 'contact@agrihub.bj',
                'tel' => '+229 01 23 45 67',
                'bp' => 'BP 123 Cotonou',
                'departement' => 'Littoral',
                'commune' => 'Cotonou',
                'arrondissement' => '1er Arrondissement',
                'description' => "AgriHub Bénin est une plateforme innovante créée pour harmoniser les relations entre les acteurs du secteur élevage.",
                'facebook' => '#',
                'whatsapp' => '#',
                'linkedin' => '#',
                'twitter' => '#',
                'instagram' => '#',
                'photo_de_vue' => null
            ];
        }
        
        // Partager avec toutes les vues
        View::share('settings', $settings);
    }
}