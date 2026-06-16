<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class NourritureDemoSeeder extends Seeder
{
    public function run(): void
    {
        $vendeurNourriture = DB::table('users')
            ->join('type_user', 'users.id_type_user', '=', 'type_user.id')
            ->where('type_user.type', 'vendeur_nourriture')
            ->select('users.id as user_id')
            ->first();

        if (!$vendeurNourriture) {
            // Créer un vendeur de nourriture par défaut si absent
            $typeVendeur = DB::table('type_user')->where('type', 'vendeur_nourriture')->first();
            if ($typeVendeur) {
                $userId = DB::table('users')->insertGetId([
                    'id_type_user' => $typeVendeur->id,
                    'nom' => 'NOURRITURE',
                    'prenom' => 'Vendeur',
                    'departement' => 'Littoral',
                    'commune' => 'Cotonou',
                    'ville' => 'Cotonou',
                    'email' => 'vendeur.nourriture@agrihub.bj',
                    'mot_de_passe_hash' => bcrypt('password123'),
                    'telephone' => '+229 97000010',
                    'adresse' => 'Cotonou, Bénin',
                    'date_inscription' => Carbon::now(),
                    'statut' => 'actif',
                    'email_verified_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::table('vendeur_nourriture')->insert([
                    'id_user' => $userId,
                    'nom_entreprise' => 'AgriAliments Bénin',
                    'description' => 'Fournisseur d\'aliments pour animaux',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $vendeurNourriture = (object)['user_id' => $userId];
            }
        }

        if ($vendeurNourriture) {
            $nourritures = [
                ['type' => 'provende', 'nom' => 'Provende pondeuse', 'description' => 'Aliment complet pour poules pondeuses'],
                ['type' => 'provende', 'nom' => 'Provende poulet de chair', 'description' => 'Aliment pour poulet de chair starter et finisher'],
                ['type' => 'provende', 'nom' => 'Aliment bétail', 'description' => 'Aliment concentré pour bovins et ovins'],
                ['type' => 'aliment_local', 'nom' => 'Son de blé', 'description' => 'Son de blé pour l\'alimentation animale'],
                ['type' => 'aliment_local', 'nom' => 'Tourteau de coton', 'description' => 'Tourteau de coton - source de protéines'],
                ['type' => 'autre', 'nom' => 'Mélange céréales', 'description' => 'Mélange de céréales pour chevaux'],
            ];

            foreach ($nourritures as $nourriture) {
                DB::table('nourriture')->insert([
                    'id_user' => $vendeurNourriture->user_id,
                    'type' => $nourriture['type'],
                    'nom' => $nourriture['nom'],
                    'description' => $nourriture['description'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}