<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleveur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nom_elevage', 200)->nullable();
            $table->text('description_elevage')->nullable();
            $table->string('localisation_gps', 100)->nullable();
            $table->string('siret', 50)->nullable()->comment('Si professionnel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleveur');
    }
};