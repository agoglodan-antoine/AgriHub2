<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonce', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('restrict')->comment('Auteur de l\'annonce');
            $table->string('titre', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['animal', 'escrement', 'nourriture', 'accessoire']);
            $table->foreignId('id_animal')->nullable()->constrained('animaux')->onDelete('set null');
            $table->foreignId('id_escrement')->nullable()->constrained('escrement')->onDelete('set null');
            $table->foreignId('id_nourriture')->nullable()->constrained('nourriture')->onDelete('set null');
            $table->foreignId('id_accessoire')->nullable()->constrained('accessoire')->onDelete('set null');
            $table->decimal('quantite', 10, 2)->nullable();
            $table->decimal('prix', 12, 2);
            $table->enum('statut', ['en_attente', 'active', 'vendue', 'expiree'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonce');
    }
};