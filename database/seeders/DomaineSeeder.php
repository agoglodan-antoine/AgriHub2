<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DomaineSeeder extends Seeder
{
    public function run(): void
    {
        $domaines = [
            ['nom' => 'animaux_compagnie', 'label' => 'Animaux de compagnie', 'description' => 'Animaux domestiques non destinés à la production'],
            ['nom' => 'elevage_viande', 'label' => 'Élevage viande', 'description' => 'Animaux élevés pour la production de viande'],
            ['nom' => 'elevage_lait', 'label' => 'Élevage lait', 'description' => 'Animaux élevés pour la production laitière'],
            ['nom' => 'aviculture', 'label' => 'Aviculture', 'description' => 'Élevage de volailles'],
            ['nom' => 'elevage_mixte', 'label' => 'Élevage mixte', 'description' => 'Animaux à usages multiples'],
        ];

        foreach ($domaines as $domaine) {
            DB::table('domaine')->insert([
                'nom' => $domaine['nom'],
                'label' => $domaine['label'],
                'description' => $domaine['description'],
                'icone' => null,
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}