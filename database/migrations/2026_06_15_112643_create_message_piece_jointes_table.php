<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_piece_jointes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_message')->constrained('message')->onDelete('cascade')->comment('ID du message');
            $table->enum('type_media', ['image', 'video', 'document', 'audio']);
            $table->string('nom_media', 255);
            $table->bigInteger('taille')->unsigned()->default(0)->comment('Taille en octets');
            $table->string('chemin_stockage', 500)->nullable();
            $table->enum('statut', ['actif', 'supprime', 'en_attente'])->default('actif');
            $table->timestamps();
            
            $table->index('statut');
            $table->index('type_media');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_piece_jointes');
    }
};