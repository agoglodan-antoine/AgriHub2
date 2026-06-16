<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_veterinaire')->constrained('users')->onDelete('restrict');
            $table->foreignId('id_client')->constrained('users')->onDelete('restrict')->comment('Éleveur ou acheteur');
            $table->string('sujet', 255);
            $table->text('description')->nullable();
            $table->dateTime('date_prevue');
            $table->enum('statut', ['en_attente', 'confirme', 'realise', 'annule'])->default('en_attente');
            $table->text('avis_client')->nullable();
            $table->tinyInteger('note')->unsigned()->nullable()->comment('Note de 1 à 5');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};