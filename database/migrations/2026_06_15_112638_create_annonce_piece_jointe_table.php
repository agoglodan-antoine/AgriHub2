<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonce_piece_jointe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_annonce')->constrained('annonce')->onDelete('cascade')->comment('ID de l\'annonce');
            $table->enum('type_media', ['image', 'video', 'document']);
            $table->string('nom_media', 255);
            $table->bigInteger('taille')->unsigned()->default(0)->comment('Taille en octets');
            $table->string('chemin_stockage', 500)->nullable();
            $table->boolean('est_principale')->default(false)->comment('Image principale');
            $table->smallInteger('ordre_affichage')->unsigned()->default(0);
            $table->enum('statut', ['actif', 'supprime', 'en_attente'])->default('actif');
            $table->timestamps();
            
            $table->index('statut');
            $table->index('type_media');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonce_piece_jointe');
    }
};