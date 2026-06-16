<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AnimauxDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer TOUS les éleveurs (pas seulement un avec un email spécifique)
        $eleveurs = DB::table('users')
            ->join('type_user', 'users.id_type_user', '=', 'type_user.id')
            ->where('type_user.type', 'eleveur')
            ->select('users.id as user_id')
            ->get();

        if ($eleveurs->isEmpty()) {
            $this->command->warn('Aucun éleveur trouvé. Impossible de créer des animaux.');
            return;
        }

        // Utiliser le premier éleveur
        $eleveur = $eleveurs->first();

        $races = DB::table('races')->pluck('id', 'nom');

        // Vérifier que les races existent
        if ($races->isEmpty()) {
            $this->command->warn('Aucune race trouvée. Exécutez d\'abord RacesSeeder.');
            return;
        }

        $animaux = [
            [
                'nom' => 'Borgou 1', 
                'age_mois' => 24, 
                'sexe' => 'M', 
                'race' => 'borgou', 
                'description' => 'Taureau Borgou de 2 ans, très vigoureux', 
                'statut' => 'disponible'
            ],
            [
                'nom' => 'Azawak 1', 
                'age_mois' => 30, 
                'sexe' => 'F', 
                'race' => 'azawak', 
                'description' => 'Vache laitière Azawak, 5 litres/jour', 
                'statut' => 'disponible'
            ],
            [
                'nom' => 'Djallonké 1', 
                'age_mois' => 12, 
                'sexe' => 'M', 
                'race' => 'djallonke', 
                'description' => 'Mouton Djallonké de 1 an', 
                'statut' => 'disponible'
            ],
            [
                'nom' => 'Chèvre Naine 1', 
                'age_mois' => 8, 
                'sexe' => 'F', 
                'race' => 'chevre_naine', 
                'description' => 'Chèvre naine pour élevage', 
                'statut' => 'disponible'
            ],
            [
                'nom' => 'Poulet F1', 
                'age_mois' => 5, 
                'sexe' => 'F', 
                'race' => 'local_benin', 
                'description' => 'Poule locale du Bénin', 
                'statut' => 'disponible'
            ],
            // Ajout d'animaux supplémentaires pour avoir plus de choix
            [
                'nom' => 'Borgou 2', 
                'age_mois' => 36, 
                'sexe' => 'M', 
                'race' => 'borgou', 
                'description' => 'Taureau Borgou reproducteur, 3 ans', 
                'statut' => 'disponible'
            ],
            [
                'nom' => 'Azawak 2', 
                'age_mois' => 24, 
                'sexe' => 'F', 
                'race' => 'azawak', 
                'description' => 'Génisse Azawak, première lactation', 
                'statut' => 'disponible'
            ],
            [
                'nom' => 'Djallonké 2', 
                'age_mois' => 8, 
                'sexe' => 'F', 
                'race' => 'djallonke', 
                'description' => 'Brebis Djallonké', 
                'statut' => 'disponible'
            ],
            [
                'nom' => 'Chèvre Naine 2', 
                'age_mois' => 6, 
                'sexe' => 'M', 
                'race' => 'chevre_naine', 
                'description' => 'Bouc nain reproducteur', 
                'statut' => 'disponible'
            ],
            [
                'nom' => 'Poulet F2', 
                'age_mois' => 4, 
                'sexe' => 'M', 
                'race' => 'local_benin', 
                'description' => 'Coq local du Bénin', 
                'statut' => 'disponible'
            ],
        ];

        $count = 0;
        foreach ($animaux as $animal) {
            // Vérifier si la race existe
            if (!isset($races[$animal['race']])) {
                $this->command->warn("Race '{$animal['race']}' non trouvée, annonce ignorée.");
                continue;
            }

            DB::table('animaux')->insert([
                'id_user' => $eleveur->user_id,
                'nom' => $animal['nom'],
                'age_mois' => $animal['age_mois'],
                'sexe' => $animal['sexe'],
                'id_race' => $races[$animal['race']],
                'description' => $animal['description'],
                'statut' => $animal['statut'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $count++;
        }

        $this->command->info("{$count} animaux créés avec succès pour l'éleveur ID: {$eleveur->user_id}");
    }
}