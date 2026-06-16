<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AccessoireDemoSeeder extends Seeder
{
    public function run(): void
    {
        $vendeurAccessoire = DB::table('users')
            ->join('type_user', 'users.id_type_user', '=', 'type_user.id')
            ->where('type_user.type', 'vendeur_accessoire')
            ->select('users.id as user_id')
            ->first();

        if (!$vendeurAccessoire) {
            $typeVendeur = DB::table('type_user')->where('type', 'vendeur_accessoire')->first();
            if ($typeVendeur) {
                $userId = DB::table('users')->insertGetId([
                    'id_type_user' => $typeVendeur->id,
                    'nom' => 'ACCESSOIRES',
                    'prenom' => 'Vendeur',
                    'departement' => 'Littoral',
                    'commune' => 'Cotonou',
                    'ville' => 'Cotonou',
                    'email' => 'vendeur.accessoires@agrihub.bj',
                    'mot_de_passe_hash' => bcrypt('password123'),
                    'telephone' => '+229 97000011',
                    'adresse' => 'Cotonou, Bénin',
                    'date_inscription' => Carbon::now(),
                    'statut' => 'actif',
                    'email_verified_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::table('vendeur_accessoire')->insert([
                    'id_user' => $userId,
                    'nom_entreprise' => 'AgriEquipements Bénin',
                    'description' => 'Fournisseur d\'équipements pour l\'élevage',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $vendeurAccessoire = (object)['user_id' => $userId];
            }
        }

        if ($vendeurAccessoire) {
            $accessoires = [
                ['nom' => 'Mangeoire automatique', 'categorie' => 'Alimentation'],
                ['nom' => 'Abreuvoir acier', 'categorie' => 'Hydratation'],
                ['nom' => 'Clôture électrique', 'categorie' => 'Enclos'],
                ['nom' => 'Couveuse artificielle', 'categorie' => 'Reproduction'],
                ['nom' => 'Matériel de traite', 'categorie' => 'Équipement'],
                ['nom' => 'Brosse à lécher', 'categorie' => 'Bien-être animal'],
            ];

            foreach ($accessoires as $accessoire) {
                DB::table('accessoire')->insert([
                    'id_user' => $vendeurAccessoire->user_id,
                    'nom' => $accessoire['nom'],
                    'categorie' => $accessoire['categorie'],
                    'description' => 'Description de ' . $accessoire['nom'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}