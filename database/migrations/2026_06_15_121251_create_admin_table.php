<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->unique()->constrained('users')->onDelete('cascade')->comment('Référence vers l\'utilisateur');
            $table->enum('type_admin', ['super_admin', 'admin_secondaire'])->default('admin_secondaire')->comment('Type d\'administrateur');
            $table->text('description')->nullable()->comment('Description du rôle ou des responsabilités');
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif')->comment('Statut de l\'administrateur');
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index('type_admin');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};