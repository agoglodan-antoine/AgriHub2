<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer le type administrateur
        $typeAdmin = DB::table('type_user')->where('type', 'administrateur')->first();
        
        if (!$typeAdmin) {
            $typeAdminId = DB::table('type_user')->insertGetId([
                'type' => 'administrateur',
                'label' => 'Administrateur plateforme',
                'description' => 'Gestionnaire de la plateforme',
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $typeAdminId = $typeAdmin->id;
        }

        // ============================================
        // SUPER ADMIN - LASSISSOU Missigbèto Zakari Yaoo
        // ============================================
        $superAdminUser = DB::table('users')->where('email', 'zakari@agrihub.bj')->first();
        
        if (!$superAdminUser) {
            $superAdminId = DB::table('users')->insertGetId([
                'id_type_user' => $typeAdminId,
                'nom' => 'LASSISSOU',
                'prenom' => 'Missigbèto Zakari Yaoo',
                'departement' => 'Littoral',
                'commune' => 'Cotonou',
                'ville' => 'Cotonou',
                'email' => 'zakari@agrihub.bj',
                'mot_de_passe_hash' => Hash::make('Zakari2024!'),
                'telephone' => '+229 90000001',
                'adresse' => 'Cotonou, Bénin',
                'date_inscription' => Carbon::now(),
                'statut' => 'actif',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $superAdminId = $superAdminUser->id;
        }

        $existingSuperAdmin = DB::table('admin')->where('id_user', $superAdminId)->first();
        
        if (!$existingSuperAdmin) {
            DB::table('admin')->insert([
                'id_user' => $superAdminId,
                'type_admin' => 'super_admin',
                'description' => 'Super administrateur - Fondateur et développeur principal de la plateforme',
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // ============================================
        // SUPER ADMIN - AGOGLODAN Antoine
        // ============================================
        $superAdminUser2 = DB::table('users')->where('email', 'antoine@agrihub.bj')->first();
        
        if (!$superAdminUser2) {
            $superAdminId2 = DB::table('users')->insertGetId([
                'id_type_user' => $typeAdminId,
                'nom' => 'AGOGLODAN',
                'prenom' => 'Antoine',
                'departement' => 'Littoral',
                'commune' => 'Cotonou',
                'ville' => 'Cotonou',
                'email' => 'antoine@agrihub.bj',
                'mot_de_passe_hash' => Hash::make('Antoine2024!'),
                'telephone' => '+229 90000002',
                'adresse' => 'Cotonou, Bénin',
                'date_inscription' => Carbon::now(),
                'statut' => 'actif',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $superAdminId2 = $superAdminUser2->id;
        }

        $existingSuperAdmin2 = DB::table('admin')->where('id_user', $superAdminId2)->first();
        
        if (!$existingSuperAdmin2) {
            DB::table('admin')->insert([
                'id_user' => $superAdminId2,
                'type_admin' => 'super_admin',
                'description' => 'Super administrateur - Fondateur et développeur principal de la plateforme',
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // ============================================
        // ADMIN SECONDAIRE 1 - Gestion des utilisateurs
        // ============================================
        $adminSecondaire1 = DB::table('users')->where('email', 'admin.users@elevage.com')->first();
        
        if (!$adminSecondaire1) {
            $adminId1 = DB::table('users')->insertGetId([
                'id_type_user' => $typeAdminId,
                'nom' => 'GESTION',
                'prenom' => 'User',
                'departement' => 'Littoral',
                'commune' => 'Cotonou',
                'ville' => 'Cotonou',
                'email' => 'admin.users@elevage.com',
                'mot_de_passe_hash' => Hash::make('Admin123!'),
                'telephone' => '+229 90000003',
                'adresse' => 'Cotonou, Bénin',
                'date_inscription' => Carbon::now(),
                'statut' => 'actif',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $adminId1 = $adminSecondaire1->id;
        }

        $existingAdmin1 = DB::table('admin')->where('id_user', $adminId1)->first();
        
        if (!$existingAdmin1) {
            DB::table('admin')->insert([
                'id_user' => $adminId1,
                'type_admin' => 'admin_secondaire',
                'description' => 'Administrateur chargé de la gestion des utilisateurs',
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // ============================================
        // ADMIN SECONDAIRE 2 - Gestion des annonces
        // ============================================
        $adminSecondaire2 = DB::table('users')->where('email', 'admin.annonces@elevage.com')->first();
        
        if (!$adminSecondaire2) {
            $adminId2 = DB::table('users')->insertGetId([
                'id_type_user' => $typeAdminId,
                'nom' => 'GESTION',
                'prenom' => 'Annonce',
                'departement' => 'Atlantique',
                'commune' => 'Ouidah',
                'ville' => 'Ouidah',
                'email' => 'admin.annonces@elevage.com',
                'mot_de_passe_hash' => Hash::make('Admin123!'),
                'telephone' => '+229 90000004',
                'adresse' => 'Ouidah, Bénin',
                'date_inscription' => Carbon::now(),
                'statut' => 'actif',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $adminId2 = $adminSecondaire2->id;
        }

        $existingAdmin2 = DB::table('admin')->where('id_user', $adminId2)->first();
        
        if (!$existingAdmin2) {
            DB::table('admin')->insert([
                'id_user' => $adminId2,
                'type_admin' => 'admin_secondaire',
                'description' => 'Administrateur chargé de la modération des annonces',
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // ============================================
        // ADMIN SECONDAIRE 3 - Gestion des transactions
        // ============================================
        $adminSecondaire3 = DB::table('users')->where('email', 'admin.transactions@elevage.com')->first();
        
        if (!$adminSecondaire3) {
            $adminId3 = DB::table('users')->insertGetId([
                'id_type_user' => $typeAdminId,
                'nom' => 'GESTION',
                'prenom' => 'Transaction',
                'departement' => 'Ouémé',
                'commune' => 'Porto-Novo',
                'ville' => 'Porto-Novo',
                'email' => 'admin.transactions@elevage.com',
                'mot_de_passe_hash' => Hash::make('Admin123!'),
                'telephone' => '+229 90000005',
                'adresse' => 'Porto-Novo, Bénin',
                'date_inscription' => Carbon::now(),
                'statut' => 'actif',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            $adminId3 = $adminSecondaire3->id;
        }

        $existingAdmin3 = DB::table('admin')->where('id_user', $adminId3)->first();
        
        if (!$existingAdmin3) {
            DB::table('admin')->insert([
                'id_user' => $adminId3,
                'type_admin' => 'admin_secondaire',
                'description' => 'Administrateur chargé du suivi des transactions',
                'statut' => 'actif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('Administrateurs créés avec succès :');
        $this->command->info('  - Super Admin: zakari@agrihub.bj / Zakari2024!');
        $this->command->info('  - Super Admin: antoine@agrihub.bj / Antoine2024!');
        $this->command->info('  - Admin Users: admin.users@elevage.com / Admin123!');
        $this->command->info('  - Admin Annonces: admin.annonces@elevage.com / Admin123!');
        $this->command->info('  - Admin Transactions: admin.transactions@elevage.com / Admin123!');
    }
}