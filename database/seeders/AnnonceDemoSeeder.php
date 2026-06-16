<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AnnonceDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les utilisateurs par type
        $eleveur = DB::table('users')
            ->join('type_user', 'users.id_type_user', '=', 'type_user.id')
            ->where('type_user.type', 'eleveur')
            ->select('users.id as user_id')
            ->first();

        $vendeurNourriture = DB::table('users')
            ->join('type_user', 'users.id_type_user', '=', 'type_user.id')
            ->where('type_user.type', 'vendeur_nourriture')
            ->select('users.id as user_id')
            ->first();

        $vendeurAccessoire = DB::table('users')
            ->join('type_user', 'users.id_type_user', '=', 'type_user.id')
            ->where('type_user.type', 'vendeur_accessoire')
            ->select('users.id as user_id')
            ->first();

        // Récupérer les IDs existants
        $animaux = DB::table('animaux')->pluck('id')->toArray();
        $escrements = DB::table('escrement')->pluck('id')->toArray();
        $nourritures = DB::table('nourriture')->pluck('id')->toArray();
        $accessoires = DB::table('accessoire')->pluck('id')->toArray();

        // Si pas de données, on ne continue pas
        if (empty($animaux) && empty($escrements) && empty($nourritures) && empty($accessoires)) {
            $this->command->info('Aucune donnée de base trouvée pour créer des annonces.');
            return;
        }

        $now = Carbon::now();

        // ============================================
        // 1. ANNONCES D'ANIMAUX
        // ============================================
        if (!empty($animaux) && $eleveur) {
            $animauxAnnonces = [
                [
                    'titre' => 'Vente de taureau Borgou',
                    'description' => 'Superbe taureau de race Borgou, 2 ans, très vigoureux. Idéal pour la reproduction. Vaccins à jour, parfait état de santé.',
                    'prix' => 450000,
                    'quantite' => 1,
                ],
                [
                    'titre' => 'Vache laitière Azawak',
                    'description' => 'Vache Azawak de 3 ans, excellente productrice laitière (environ 5 litres/jour). Très docile, idéale pour débutant.',
                    'prix' => 350000,
                    'quantite' => 1,
                ],
                [
                    'titre' => 'Lot de 10 moutons Djallonké',
                    'description' => 'Lot de 10 moutons Djallonké (8 femelles + 2 mâles). Très résistants, excellente viande. Vaccinés et vermifugés.',
                    'prix' => 500000,
                    'quantite' => 10,
                ],
                [
                    'titre' => 'Chèvres naines de qualité',
                    'description' => 'Lot de 5 chèvres naines (4 femelles gestantes + 1 bouc). Idéal pour démarrer un élevage caprin.',
                    'prix' => 250000,
                    'quantite' => 5,
                ],
                [
                    'titre' => 'Poulets locaux du Bénin',
                    'description' => 'Lot de 50 poulets locaux (30 poules + 20 coqs). Race robuste, excellente pondeuse.',
                    'prix' => 150000,
                    'quantite' => 50,
                ],
                [
                    'titre' => 'Taureau reproducteur',
                    'description' => 'Taureau reproducteur de race Borgou, 4 ans. Lignée pure, nombreux veaux à son actif.',
                    'prix' => 650000,
                    'quantite' => 1,
                ],
            ];

            foreach ($animauxAnnonces as $index => $annonce) {
                $animalId = $animaux[$index % count($animaux)];
                
                DB::table('annonce')->insert([
                    'id_user' => $eleveur->user_id,
                    'titre' => $annonce['titre'],
                    'description' => $annonce['description'],
                    'type' => 'animal',
                    'id_animal' => $animalId,
                    'id_escrement' => null,
                    'id_nourriture' => null,
                    'id_accessoire' => null,
                    'quantite' => $annonce['quantite'],
                    'prix' => $annonce['prix'],
                    'statut' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ============================================
        // 2. ANNONCES D'ESCREMENTS / FUMIER
        // ============================================
        if (!empty($escrements) && $eleveur) {
            $escrementsAnnonces = [
                [
                    'titre' => 'Fumier de bovin - Engrais naturel',
                    'description' => 'Fumier de bovin bien décomposé, idéal pour l\'agriculture biologique. Livraison possible en grande quantité.',
                    'prix' => 25000,
                    'quantite' => 1000,
                ],
                [
                    'titre' => 'Fiente de poulet - Excellent engrais',
                    'description' => 'Fiente de poulet séchée et conditionnée. Riche en azote, parfait pour les cultures maraîchères.',
                    'prix' => 35000,
                    'quantite' => 500,
                ],
                [
                    'titre' => 'Fumier de mouton',
                    'description' => 'Fumier de mouton de première qualité. Idéal pour l\'amendement des sols.',
                    'prix' => 20000,
                    'quantite' => 800,
                ],
                [
                    'titre' => 'Compost bio - Fumier mélangé',
                    'description' => 'Compost à base de fumier de bovin et de fiente de volaille. Parfait pour vos cultures.',
                    'prix' => 30000,
                    'quantite' => 600,
                ],
            ];

            foreach ($escrementsAnnonces as $index => $annonce) {
                $escrementId = $escrements[$index % count($escrements)];
                
                DB::table('annonce')->insert([
                    'id_user' => $eleveur->user_id,
                    'titre' => $annonce['titre'],
                    'description' => $annonce['description'],
                    'type' => 'escrement',
                    'id_animal' => null,
                    'id_escrement' => $escrementId,
                    'id_nourriture' => null,
                    'id_accessoire' => null,
                    'quantite' => $annonce['quantite'],
                    'prix' => $annonce['prix'],
                    'statut' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ============================================
        // 3. ANNONCES DE NOURRITURE / PROVENDE
        // ============================================
        if (!empty($nourritures) && $vendeurNourriture) {
            $nourrituresAnnonces = [
                [
                    'titre' => 'Provende pour volaille pondeuse',
                    'description' => 'Aliment complet pour poules pondeuses. Composition équilibrée pour une production optimale d\'œufs.',
                    'prix' => 18500,
                    'quantite' => 100,
                ],
                [
                    'titre' => 'Aliment bétail - Engraissement',
                    'description' => 'Aliment concentré pour l\'engraissement des bovins et ovins. Très efficace, résultats garantis.',
                    'prix' => 22000,
                    'quantite' => 200,
                ],
                [
                    'titre' => 'Son de blé - Alimentation animale',
                    'description' => 'Son de blé de qualité supérieure pour l\'alimentation des animaux. Riche en fibres.',
                    'prix' => 12500,
                    'quantite' => 150,
                ],
                [
                    'titre' => 'Provende pour poulet de chair',
                    'description' => 'Aliment starter et finisher pour poulet de chair. Croissance rapide et viande de qualité.',
                    'prix' => 19500,
                    'quantite' => 120,
                ],
                [
                    'titre' => 'Tourteau de coton',
                    'description' => 'Tourteau de coton, excellente source de protéines pour l\'alimentation animale.',
                    'prix' => 15000,
                    'quantite' => 300,
                ],
                [
                    'titre' => 'Mélange céréales - Alimentation chevaux',
                    'description' => 'Mélange équilibré d\'avoine, maïs et orge pour chevaux. Energie et vitalité.',
                    'prix' => 21000,
                    'quantite' => 80,
                ],
            ];

            foreach ($nourrituresAnnonces as $index => $annonce) {
                $nourritureId = $nourritures[$index % count($nourritures)];
                
                DB::table('annonce')->insert([
                    'id_user' => $vendeurNourriture->user_id,
                    'titre' => $annonce['titre'],
                    'description' => $annonce['description'],
                    'type' => 'nourriture',
                    'id_animal' => null,
                    'id_escrement' => null,
                    'id_nourriture' => $nourritureId,
                    'id_accessoire' => null,
                    'quantite' => $annonce['quantite'],
                    'prix' => $annonce['prix'],
                    'statut' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ============================================
        // 4. ANNONCES D'ACCESSOIRES
        // ============================================
        if (!empty($accessoires) && $vendeurAccessoire) {
            $accessoiresAnnonces = [
                [
                    'titre' => 'Mangeoire automatique pour volaille',
                    'description' => 'Mangeoire automatique en plastique résistant. Capacité 20kg. Idéal pour les élevages de poulets.',
                    'prix' => 8500,
                    'quantite' => 50,
                ],
                [
                    'titre' => 'Abreuvoir pour bétail',
                    'description' => 'Abreuvoir en acier galvanisé, capacité 100L. Robuste et durable, résiste aux intempéries.',
                    'prix' => 45000,
                    'quantite' => 10,
                ],
                [
                    'titre' => 'Kit de clôture électrique',
                    'description' => 'Kit complet de clôture électrique pour pâturage. Contient 200m de fil, 10 piquets et l\'électrificateur.',
                    'prix' => 75000,
                    'quantite' => 5,
                ],
                [
                    'titre' => 'Couveuse artificielle 100 œufs',
                    'description' => 'Couveuse automatique pour 100 œufs. Contrôle température et hygrométrie. Idéal pour l\'aviculture.',
                    'prix' => 125000,
                    'quantite' => 3,
                ],
                [
                    'titre' => 'Matériel de traite manuelle',
                    'description' => 'Kit complet de traite manuelle : seau, tamis, tuyaux. Pour vaches et chèvres.',
                    'prix' => 35000,
                    'quantite' => 15,
                ],
                [
                    'titre' => 'Brosse à lécher pour bovins',
                    'description' => 'Brosse rotative automatique pour le bien-être des bovins. Améliore la santé et la production.',
                    'prix' => 95000,
                    'quantite' => 4,
                ],
            ];

            foreach ($accessoiresAnnonces as $index => $annonce) {
                $accessoireId = $accessoires[$index % count($accessoires)];
                
                DB::table('annonce')->insert([
                    'id_user' => $vendeurAccessoire->user_id,
                    'titre' => $annonce['titre'],
                    'description' => $annonce['description'],
                    'type' => 'accessoire',
                    'id_animal' => null,
                    'id_escrement' => null,
                    'id_nourriture' => null,
                    'id_accessoire' => $accessoireId,
                    'quantite' => $annonce['quantite'],
                    'prix' => $annonce['prix'],
                    'statut' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ============================================
        // 5. ANNONCES EN ATTENTE DE VALIDATION
        // ============================================
        if ($eleveur) {
            $enAttenteAnnonces = [
                [
                    'titre' => 'Nouveau lot d\'agneaux disponibles',
                    'description' => 'Lot de 15 agneaux Djallonké. 2 mois, sevrés, vaccinés. Très bons gabarits.',
                    'prix' => 225000,
                    'quantite' => 15,
                    'type' => 'animal',
                ],
                [
                    'titre' => 'Fumier frais de bovin',
                    'description' => 'Fumier frais disponible immédiatement. Livraison possible dans tout le département.',
                    'prix' => 15000,
                    'quantite' => 1000,
                    'type' => 'escrement',
                ],
            ];

            foreach ($enAttenteAnnonces as $annonce) {
                $animalId = !empty($animaux) ? $animaux[0] : null;
                $escrementId = !empty($escrements) ? $escrements[0] : null;
                
                DB::table('annonce')->insert([
                    'id_user' => $eleveur->user_id,
                    'titre' => $annonce['titre'],
                    'description' => $annonce['description'],
                    'type' => $annonce['type'],
                    'id_animal' => $annonce['type'] == 'animal' ? $animalId : null,
                    'id_escrement' => $annonce['type'] == 'escrement' ? $escrementId : null,
                    'id_nourriture' => null,
                    'id_accessoire' => null,
                    'quantite' => $annonce['quantite'],
                    'prix' => $annonce['prix'],
                    'statut' => 'en_attente',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('Annonces de démonstration créées avec succès !');
    }
}