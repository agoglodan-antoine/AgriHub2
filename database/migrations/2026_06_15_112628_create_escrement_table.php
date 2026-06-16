<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escrement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('restrict')->comment('Producteur (éleveur)');
            $table->foreignId('id_espece')->constrained('especes')->onDelete('restrict')->comment('Espèce source');
            $table->string('nom', 150)->comment('Ex : Fumier de bœuf, Fiente de volaille');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escrement');
    }
};