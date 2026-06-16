<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TypeUserSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['type' => 'eleveur', 'label' => 'Éleveur', 'description' => 'Producteur d\'animaux (bétail, volaille, etc.)'],
            ['type' => 'acheteur', 'label' => 'Acheteur / Client', 'description' => 'Individu ou entreprise cherchant à acheter'],
            ['type' => 'veterinaire', 'label' => 'Vétérinaire', 'description' => 'Professionnel de la santé animale'],
            ['type' => 'transporteur', 'label' => 'Transporteur', 'description' => 'Fournisseur de services logistiques'],
            ['type' => 'vendeur_nourriture', 'label' => 'Vendeur de nourriture', 'description' => 'Fournisseur de provende et aliments pour animaux'],
            ['type' => 'vendeur_accessoire', 'label' => 'Vendeur d\'accessoires', 'description' => 'Fournisseur d\'équipements et accessoires d\'élevage'],
            ['type' => 'administrateur', 'label' => 'Administrateur plateforme', 'description' => 'Gestionnaire de la plateforme'],
        ];

        foreach ($types as $type) {
            DB::table('type_user')->insert([
                'type' => $type['type'],
                'label' => $type['label'],
                'description' => $type['description'],
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}