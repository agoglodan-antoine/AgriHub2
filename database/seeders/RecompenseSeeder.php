<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RecompenseSeeder extends Seeder
{
    public function run(): void
    {
        $recompenses = [
            ['nom_recompense' => 'Livraison Gratuite', 'description' => 'Offre la livraison gratuite sur votre prochaine commande', 'cout_points' => 500, 'type_recompense' => 'livraison'],
            ['nom_recompense' => 'Réduction 10%', 'description' => 'Obtenez 10% de réduction sur votre prochain achat', 'cout_points' => 1000, 'type_recompense' => 'reduction'],
            ['nom_recompense' => 'Réduction 20%', 'description' => 'Obtenez 20% de réduction sur votre prochain achat', 'cout_points' => 2000, 'type_recompense' => 'reduction'],
            ['nom_recompense' => 'Consultation Vétérinaire Gratuite', 'description' => 'Une consultation vétérinaire offerte', 'cout_points' => 1500, 'type_recompense' => 'service'],
            ['nom_recompense' => 'Sac d\'Aliment Premium', 'description' => 'Un sac de 25kg d\'aliment premium offert', 'cout_points' => 3000, 'type_recompense' => 'produit'],
        ];

        foreach ($recompenses as $recompense) {
            DB::table('recompense')->insert([
                'nom_recompense' => $recompense['nom_recompense'],
                'description' => $recompense['description'],
                'cout_points' => $recompense['cout_points'],
                'type_recompense' => $recompense['type_recompense'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}