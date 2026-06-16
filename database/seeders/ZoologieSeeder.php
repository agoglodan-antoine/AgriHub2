<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ZoologieSeeder extends Seeder
{
    public function run(): void
    {
        $zoologies = [
            ['nom' => 'mammiferes', 'label' => 'Mammifères', 'description' => 'Animaux à sang chaud, vertébrés'],
            ['nom' => 'volailles', 'label' => 'Volailles', 'description' => 'Oiseaux domestiques d\'élevage'],
            ['nom' => 'reptiles', 'label' => 'Reptiles', 'description' => 'Reptiles d\'élevage ou de compagnie'],
            ['nom' => 'poissons', 'label' => 'Poissons', 'description' => 'Poissons d\'élevage (aquaculture)'],
        ];

        foreach ($zoologies as $zoologie) {
            DB::table('zoologie')->insert([
                'nom' => $zoologie['nom'],
                'label' => $zoologie['label'],
                'description' => $zoologie['description'],
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}