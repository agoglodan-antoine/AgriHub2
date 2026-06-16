<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UsersDemoSeeder extends Seeder
{
    public function run(): void
    {
        $types = DB::table('type_user')->pluck('id', 'type');

        // ============================================
        // 1. ÉLEVEURS (2 éleveurs)
        // ============================================
        
        // Éleveur 1
        $eleveur1Id = DB::table('users')->insertGetId([
            'id_type_user' => $types['eleveur'],
            'nom' => 'KONE',
            'prenom' => 'Mamadou',
            'departement' => 'Collines',
            'commune' => 'Dassa-Zoumé',
            'ville' => 'Dassa',
            'email' => 'eleveur1@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'telephone' => '+229 97000001',
            'adresse' => 'Dassa-Zoumé, Bénin',
            'date_inscription' => Carbon::now(),
            'statut' => 'actif',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('eleveur')->insert([
            'id_user' => $eleveur1Id,
            'nom_elevage' => 'Ferme KONE',
            'description_elevage' => 'Élevage de bovins et volailles',
            'localisation_gps' => '7.7500° N, 2.1833° E',
            'siret' => '12345678900001',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Éleveur 2
        $eleveur2Id = DB::table('users')->insertGetId([
            'id_type_user' => $types['eleveur'],
            'nom' => 'HOUNGBEDJI',
            'prenom' => 'François',
            'departement' => 'Zou',
            'commune' => 'Abomey',
            'ville' => 'Abomey',
            'email' => 'eleveur2@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'telephone' => '+229 97000006',
            'adresse' => 'Abomey, Bénin',
            'date_inscription' => Carbon::now(),
            'statut' => 'actif',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('eleveur')->insert([
            'id_user' => $eleveur2Id,
            'nom_elevage' => 'Ferme HOUNGBEDJI',
            'description_elevage' => 'Élevage caprin et ovin',
            'localisation_gps' => '7.1833° N, 1.9833° E',
            'siret' => '12345678900002',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // ============================================
        // 2. ACHETEURS (2 acheteurs)
        // ============================================
        
        // Acheteur 1
        $acheteur1Id = DB::table('users')->insertGetId([
            'id_type_user' => $types['acheteur'],
            'nom' => 'DOSSOU',
            'prenom' => 'Aimé',
            'departement' => 'Littoral',
            'commune' => 'Cotonou',
            'ville' => 'Cotonou',
            'email' => 'acheteur1@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'telephone' => '+229 97000002',
            'adresse' => 'Cotonou, Bénin',
            'date_inscription' => Carbon::now(),
            'statut' => 'actif',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('acheteur')->insert([
            'id_user' => $acheteur1Id,
            'type_acheteur' => 'professionnel',
            'preferences_achat' => 'Bovins et volailles de qualité',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Acheteur 2
        $acheteur2Id = DB::table('users')->insertGetId([
            'id_type_user' => $types['acheteur'],
            'nom' => 'ADJOVI',
            'prenom' => 'Bénédicte',
            'departement' => 'Ouémé',
            'commune' => 'Porto-Novo',
            'ville' => 'Porto-Novo',
            'email' => 'acheteur2@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'telephone' => '+229 97000007',
            'adresse' => 'Porto-Novo, Bénin',
            'date_inscription' => Carbon::now(),
            'statut' => 'actif',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('acheteur')->insert([
            'id_user' => $acheteur2Id,
            'type_acheteur' => 'particulier',
            'preferences_achat' => 'Animaux de basse-cour',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // ============================================
        // 3. VÉTÉRINAIRES (6 vétérinaires)
        // ============================================
        
        $veterinaires = [
            [
                'nom' => 'AGOSSOU',
                'prenom' => 'Christiane',
                'departement' => 'Atlantique',
                'commune' => 'Allada',
                'ville' => 'Allada',
                'email' => 'veterinaire1@example.com',
                'telephone' => '+229 97000003',
                'numero_ordre' => 'BEN-VET-001',
                'specialites' => 'Bovins, Ovins, Caprins',
                'zone_intervention' => 'Atlantique, Littoral',
            ],
            [
                'nom' => 'HOUNDEKIN',
                'prenom' => 'Romain',
                'departement' => 'Littoral',
                'commune' => 'Cotonou',
                'ville' => 'Cotonou',
                'email' => 'veterinaire2@example.com',
                'telephone' => '+229 97000008',
                'numero_ordre' => 'BEN-VET-002',
                'specialites' => 'Volailles, Aviculture',
                'zone_intervention' => 'Littoral, Ouémé',
            ],
            [
                'nom' => 'BOKO',
                'prenom' => 'Séraphin',
                'departement' => 'Collines',
                'commune' => 'Savè',
                'ville' => 'Savè',
                'email' => 'veterinaire3@example.com',
                'telephone' => '+229 97000009',
                'numero_ordre' => 'BEN-VET-003',
                'specialites' => 'Bovins, Équins',
                'zone_intervention' => 'Collines, Borgou',
            ],
            [
                'nom' => 'TOKPON',
                'prenom' => 'Gisèle',
                'departement' => 'Zou',
                'commune' => 'Abomey',
                'ville' => 'Abomey',
                'email' => 'veterinaire4@example.com',
                'telephone' => '+229 97000010',
                'numero_ordre' => 'BEN-VET-004',
                'specialites' => 'Caprins, Ovins, Petits ruminants',
                'zone_intervention' => 'Zou, Collines',
            ],
            [
                'nom' => 'ALIDJOU',
                'prenom' => 'Moussa',
                'departement' => 'Borgou',
                'commune' => 'Parakou',
                'ville' => 'Parakou',
                'email' => 'veterinaire5@example.com',
                'telephone' => '+229 97000011',
                'numero_ordre' => 'BEN-VET-005',
                'specialites' => 'Bovins, Ovins, Grands ruminants',
                'zone_intervention' => 'Borgou, Alibori',
            ],
            [
                'nom' => 'GBEDO',
                'prenom' => 'Jules',
                'departement' => 'Mono',
                'commune' => 'Lokossa',
                'ville' => 'Lokossa',
                'email' => 'veterinaire6@example.com',
                'telephone' => '+229 97000012',
                'numero_ordre' => 'BEN-VET-006',
                'specialites' => 'Porcins, Aviculture',
                'zone_intervention' => 'Mono, Couffo',
            ],
        ];

        $veterinaireIds = [];
        foreach ($veterinaires as $veto) {
            $vetoId = DB::table('users')->insertGetId([
                'id_type_user' => $types['veterinaire'],
                'nom' => $veto['nom'],
                'prenom' => $veto['prenom'],
                'departement' => $veto['departement'],
                'commune' => $veto['commune'],
                'ville' => $veto['ville'],
                'email' => $veto['email'],
                'mot_de_passe_hash' => Hash::make('password123'),
                'telephone' => $veto['telephone'],
                'adresse' => $veto['ville'] . ', Bénin',
                'date_inscription' => Carbon::now(),
                'statut' => 'actif',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('veterinaire')->insert([
                'id_user' => $vetoId,
                'numero_ordre' => $veto['numero_ordre'],
                'specialites' => $veto['specialites'],
                'zone_intervention' => $veto['zone_intervention'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            
            $veterinaireIds[] = $vetoId;
        }

        // ============================================
        // 4. TRANSPORTEURS (6 transporteurs)
        // ============================================
        
        $transporteurs = [
            [
                'nom' => 'TRANSPORTS',
                'prenom' => 'Jean',
                'departement' => 'Ouémé',
                'commune' => 'Porto-Novo',
                'ville' => 'Porto-Novo',
                'email' => 'transport1@example.com',
                'telephone' => '+229 97000004',
                'type_vehicule' => 'Camion plateau',
                'capacite_transport' => 5000,
                'zone_intervention' => 'Bénin entier',
                'licence_transport' => 'TRANS-001',
            ],
            [
                'nom' => 'LOGISTRANS',
                'prenom' => 'Marc',
                'departement' => 'Littoral',
                'commune' => 'Cotonou',
                'ville' => 'Cotonou',
                'email' => 'transport2@example.com',
                'telephone' => '+229 97000013',
                'type_vehicule' => 'Camion frigorifique',
                'capacite_transport' => 8000,
                'zone_intervention' => 'Littoral, Atlantique, Ouémé',
                'licence_transport' => 'TRANS-002',
            ],
            [
                'nom' => 'BETRANS',
                'prenom' => 'Pierre',
                'departement' => 'Collines',
                'commune' => 'Dassa-Zoumé',
                'ville' => 'Dassa',
                'email' => 'transport3@example.com',
                'telephone' => '+229 97000014',
                'type_vehicule' => 'Camion benne',
                'capacite_transport' => 10000,
                'zone_intervention' => 'Collines, Zou, Borgou',
                'licence_transport' => 'TRANS-003',
            ],
            [
                'nom' => 'AGRI-MOVE',
                'prenom' => 'Antoine',
                'departement' => 'Atlantique',
                'commune' => 'Ouidah',
                'ville' => 'Ouidah',
                'email' => 'transport4@example.com',
                'telephone' => '+229 97000015',
                'type_vehicule' => 'Utilitaire bétaillère',
                'capacite_transport' => 3500,
                'zone_intervention' => 'Atlantique, Littoral, Mono',
                'licence_transport' => 'TRANS-004',
            ],
            [
                'nom' => 'BENIN-LIVRAISON',
                'prenom' => 'Félicien',
                'departement' => 'Borgou',
                'commune' => 'Parakou',
                'ville' => 'Parakou',
                'email' => 'transport5@example.com',
                'telephone' => '+229 97000016',
                'type_vehicule' => 'Camion plateau',
                'capacite_transport' => 6000,
                'zone_intervention' => 'Borgou, Alibori, Atacora',
                'licence_transport' => 'TRANS-005',
            ],
            [
                'nom' => 'FARM-CARGO',
                'prenom' => 'Rachel',
                'departement' => 'Zou',
                'commune' => 'Abomey',
                'ville' => 'Abomey',
                'email' => 'transport6@example.com',
                'telephone' => '+229 97000017',
                'type_vehicule' => 'Camionnette bétaillère',
                'capacite_transport' => 2000,
                'zone_intervention' => 'Zou, Collines, Plateau',
                'licence_transport' => 'TRANS-006',
            ],
        ];

        foreach ($transporteurs as $transport) {
            $transportId = DB::table('users')->insertGetId([
                'id_type_user' => $types['transporteur'],
                'nom' => $transport['nom'],
                'prenom' => $transport['prenom'],
                'departement' => $transport['departement'],
                'commune' => $transport['commune'],
                'ville' => $transport['ville'],
                'email' => $transport['email'],
                'mot_de_passe_hash' => Hash::make('password123'),
                'telephone' => $transport['telephone'],
                'adresse' => $transport['ville'] . ', Bénin',
                'date_inscription' => Carbon::now(),
                'statut' => 'actif',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('transporteur')->insert([
                'id_user' => $transportId,
                'type_vehicule' => $transport['type_vehicule'],
                'capacite_transport' => $transport['capacite_transport'],
                'zone_intervention' => $transport['zone_intervention'],
                'licence_transport' => $transport['licence_transport'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // ============================================
        // 5. VENDEURS DE NOURRITURE (2 vendeurs)
        // ============================================
        
        $vendeurNourriture1Id = DB::table('users')->insertGetId([
            'id_type_user' => $types['vendeur_nourriture'],
            'nom' => 'AGRIALIM',
            'prenom' => 'Paul',
            'departement' => 'Littoral',
            'commune' => 'Cotonou',
            'ville' => 'Cotonou',
            'email' => 'vendeur.nourriture1@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'telephone' => '+229 97000018',
            'adresse' => 'Cotonou, Bénin',
            'date_inscription' => Carbon::now(),
            'statut' => 'actif',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('vendeur_nourriture')->insert([
            'id_user' => $vendeurNourriture1Id,
            'nom_entreprise' => 'AgriAliments Bénin',
            'description' => 'Fournisseur d\'aliments pour animaux de qualité',
            'localisation_gps' => '6.3672° N, 2.4260° E',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $vendeurNourriture2Id = DB::table('users')->insertGetId([
            'id_type_user' => $types['vendeur_nourriture'],
            'nom' => 'PROVENDEX',
            'prenom' => 'Gilles',
            'departement' => 'Atlantique',
            'commune' => 'Ouidah',
            'ville' => 'Ouidah',
            'email' => 'vendeur.nourriture2@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'telephone' => '+229 97000019',
            'adresse' => 'Ouidah, Bénin',
            'date_inscription' => Carbon::now(),
            'statut' => 'actif',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('vendeur_nourriture')->insert([
            'id_user' => $vendeurNourriture2Id,
            'nom_entreprise' => 'Provendex Bénin',
            'description' => 'Fabricant et distributeur de provende pour volailles',
            'localisation_gps' => '6.3635° N, 2.0832° E',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // ============================================
        // 6. VENDEURS D'ACCESSOIRES (2 vendeurs)
        // ============================================
        
        $vendeurAccessoire1Id = DB::table('users')->insertGetId([
            'id_type_user' => $types['vendeur_accessoire'],
            'nom' => 'AGRI-EQUIP',
            'prenom' => 'Alain',
            'departement' => 'Littoral',
            'commune' => 'Cotonou',
            'ville' => 'Cotonou',
            'email' => 'vendeur.accessoire1@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'telephone' => '+229 97000020',
            'adresse' => 'Cotonou, Bénin',
            'date_inscription' => Carbon::now(),
            'statut' => 'actif',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('vendeur_accessoire')->insert([
            'id_user' => $vendeurAccessoire1Id,
            'nom_entreprise' => 'AgriEquipements Bénin',
            'description' => 'Fournisseur d\'équipements pour l\'élevage',
            'localisation_gps' => '6.3672° N, 2.4260° E',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $vendeurAccessoire2Id = DB::table('users')->insertGetId([
            'id_type_user' => $types['vendeur_accessoire'],
            'nom' => 'ELEV-MAT',
            'prenom' => 'Cécile',
            'departement' => 'Ouémé',
            'commune' => 'Porto-Novo',
            'ville' => 'Porto-Novo',
            'email' => 'vendeur.accessoire2@example.com',
            'mot_de_passe_hash' => Hash::make('password123'),
            'telephone' => '+229 97000021',
            'adresse' => 'Porto-Novo, Bénin',
            'date_inscription' => Carbon::now(),
            'statut' => 'actif',
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('vendeur_accessoire')->insert([
            'id_user' => $vendeurAccessoire2Id,
            'nom_entreprise' => 'ElevMat Bénin',
            'description' => 'Matériel et équipements pour élevage moderne',
            'localisation_gps' => '6.4969° N, 2.6283° E',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Affichage des informations dans la console
        $this->command->info('Utilisateurs de démonstration créés avec succès :');
        $this->command->info('  - Éleveurs: 2 (eleveur1@example.com, eleveur2@example.com)');
        $this->command->info('  - Acheteurs: 2 (acheteur1@example.com, acheteur2@example.com)');
        $this->command->info('  - Vétérinaires: 6 (veterinaire1@example.com à veterinaire6@example.com)');
        $this->command->info('  - Transporteurs: 6 (transport1@example.com à transport6@example.com)');
        $this->command->info('  - Vendeurs nourriture: 2');
        $this->command->info('  - Vendeurs accessoires: 2');
        $this->command->info('');
        $this->command->info('Mot de passe pour tous les comptes: password123');
    }
}