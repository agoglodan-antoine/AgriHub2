<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EspecesSeeder extends Seeder
{
    public function run(): void
    {
        $zoologies = DB::table('zoologie')->pluck('id', 'nom');
        $domaines = DB::table('domaine')->pluck('id', 'nom');

        $especes = [
            ['nom' => 'bovin', 'label' => 'Bœuf / Vache', 'zoologie' => 'mammiferes', 'domaine' => 'elevage_viande', 'description' => 'Bovin destiné à la viande et/ou au lait'],
            ['nom' => 'ovin', 'label' => 'Mouton', 'zoologie' => 'mammiferes', 'domaine' => 'elevage_viande', 'description' => 'Ovin élevé principalement pour la viande'],
            ['nom' => 'caprin', 'label' => 'Chèvre', 'zoologie' => 'mammiferes', 'domaine' => 'elevage_viande', 'description' => 'Caprin à viande et à lait'],
            ['nom' => 'porcin', 'label' => 'Porc', 'zoologie' => 'mammiferes', 'domaine' => 'elevage_viande', 'description' => 'Porcin destiné à la viande'],
            ['nom' => 'poulet', 'label' => 'Poulet', 'zoologie' => 'volailles', 'domaine' => 'aviculture', 'description' => 'Volaille la plus courante en Afrique de l\'Ouest'],
            ['nom' => 'pintade', 'label' => 'Pintade', 'zoologie' => 'volailles', 'domaine' => 'aviculture', 'description' => 'Volaille africaine très prisée'],
            ['nom' => 'canard', 'label' => 'Canard', 'zoologie' => 'volailles', 'domaine' => 'aviculture', 'description' => 'Canard domestique'],
            ['nom' => 'lapin', 'label' => 'Lapin', 'zoologie' => 'mammiferes', 'domaine' => 'animaux_compagnie', 'description' => 'Lapins de chair ou de compagnie'],
            ['nom' => 'chien', 'label' => 'Chien', 'zoologie' => 'mammiferes', 'domaine' => 'animaux_compagnie', 'description' => 'Animal de compagnie ou de garde'],
            ['nom' => 'chat', 'label' => 'Chat', 'zoologie' => 'mammiferes', 'domaine' => 'animaux_compagnie', 'description' => 'Animal de compagnie'],
            ['nom' => 'ane', 'label' => 'Âne', 'zoologie' => 'mammiferes', 'domaine' => 'elevage_mixte', 'description' => 'Animal de trait et de charge'],
        ];

        foreach ($especes as $espece) {
            DB::table('especes')->insert([
                'nom' => $espece['nom'],
                'label' => $espece['label'],
                'icone' => null,
                'id_zoologie' => $zoologies[$espece['zoologie']],
                'id_domaine' => $domaines[$espece['domaine']],
                'description' => $espece['description'],
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}