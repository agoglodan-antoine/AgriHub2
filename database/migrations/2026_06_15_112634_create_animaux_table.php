<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('restrict')->comment('Propriétaire (éleveur)');
            $table->string('nom', 100)->nullable();
            $table->smallInteger('age_mois')->unsigned()->nullable()->comment('Âge en mois');
            $table->enum('sexe', ['M', 'F']);
            $table->foreignId('id_race')->constrained('races')->onDelete('restrict');
            $table->text('description')->nullable();
            $table->enum('statut', ['disponible', 'vendu', 'reserve'])->default('disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animaux');
    }
};