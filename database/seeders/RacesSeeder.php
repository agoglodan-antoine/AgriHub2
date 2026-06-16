<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RacesSeeder extends Seeder
{
    public function run(): void
    {
        $especes = DB::table('especes')->pluck('id', 'nom');

        $races = [
            ['nom' => 'borgou', 'label' => 'Borgou', 'espece' => 'bovin', 'description' => 'Race bovine taurine du Bénin, rustique et résistante'],
            ['nom' => 'azawak', 'label' => 'Azawak', 'espece' => 'bovin', 'description' => 'Race bovine zébu du Sahel, bonne laitière'],
            ['nom' => 'djallonke', 'label' => 'Djallonké', 'espece' => 'ovin', 'description' => 'Mouton nain de l\'Afrique de l\'Ouest, résistant'],
            ['nom' => 'peul_peul', 'label' => 'Mouton Peul', 'espece' => 'ovin', 'description' => 'Mouton à grandes oreilles, zone soudano-sahélienne'],
            ['nom' => 'chevre_naine', 'label' => 'Chèvre naine', 'espece' => 'caprin', 'description' => 'Caprin nain de l\'Afrique de l\'Ouest'],
            ['nom' => 'chevre_sahel', 'label' => 'Chèvre du Sahel', 'espece' => 'caprin', 'description' => 'Chèvre sahélienne, bonne laitière'],
            ['nom' => 'local_benin', 'label' => 'Poulet local Bénin', 'espece' => 'poulet', 'description' => 'Race locale du Bénin, chair savoureuse'],
            ['nom' => 'basandji', 'label' => 'Basenji', 'espece' => 'chien', 'description' => 'Chien africain de chasse, race ancienne'],
            ['nom' => 'savane', 'label' => 'Pintade de savane', 'espece' => 'pintade', 'description' => 'Pintade sauvage semi-domestiquée'],
        ];

        foreach ($races as $race) {
            DB::table('races')->insert([
                'nom' => $race['nom'],
                'label' => $race['label'],
                'id_espece' => $especes[$race['espece']],
                'description' => $race['description'],
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}