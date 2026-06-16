<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            // 1. Tables de classification (indépendantes)
            TypeUserSeeder::class,
            DomaineSeeder::class,
            ZoologieSeeder::class,
            ParametreSeeder::class,
            
            // 2. Tables dépendantes des classifications
            EspecesSeeder::class,
            RacesSeeder::class,
            
            // 3. Récompenses
            RecompenseSeeder::class,
            
            // 4. Utilisateurs (doivent être créés AVANT les animaux)
            UsersDemoSeeder::class,
            AdminSeeder::class,
            
            // 5. DONNÉES DE BASE (dépendent des users)
            // CRÉER D'ABORD LES ANIMAUX, ESCREMENTS, NOURRITURES, ACCESSOIRES
            EscrementDemoSeeder::class,   // À créer
            NourritureDemoSeeder::class,  // À créer
            AccessoireDemoSeeder::class,  // À créer
            AnimauxDemoSeeder::class,     // ← IMPORTANT: Après UsersDemoSeeder
            
            // 6. ENSUITE LES ANNONCES (dépendent des données ci-dessus)
            AnnonceDemoSeeder::class,
        ]);
    }
}