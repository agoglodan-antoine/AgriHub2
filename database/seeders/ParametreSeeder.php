<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ParametreSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifier si les paramètres existent déjà
        $existing = DB::table('parametres')->first();
        
        if (!$existing) {
            DB::table('parametres')->insert([
                'nom_plateforme' => 'AgriHub Bénin',
                'logo' => 'logo/agrihub.png',
                'slogan' => "L'harmonie entre les acteurs du secteur élevage au Bénin",
                'mail' => 'contact@agrihub.bj',
                'tel' => '+229 01 23 45 67',
                'bp' => 'BP 123 Cotonou',
                'departement' => 'Littoral',
                'commune' => 'Cotonou',
                'arrondissement' => '1er Arrondissement',
                'facebook' => 'https://facebook.com/agrihubbenin',
                'whatsapp' => 'https://wa.me/22990000001',
                'linkedin' => 'https://linkedin.com/company/agrihubbenin',
                'twitter' => 'https://twitter.com/agrihubbenin',
                'instagram' => 'https://instagram.com/agrihubbenin',
                'description' => "AgriHub Bénin est une plateforme innovante créée pour harmoniser les relations entre les acteurs du secteur élevage. Notre mission est de connecter les éleveurs, acheteurs, vétérinaires, transporteurs et fournisseurs pour faciliter le commerce et l'échange de services dans le secteur de l'élevage au Bénin.",
                'photo_de_vue' => 'images/hero.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}