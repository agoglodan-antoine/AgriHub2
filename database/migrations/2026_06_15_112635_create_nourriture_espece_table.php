<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nourriture_espece', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nourriture')->constrained('nourriture')->onDelete('cascade');
            $table->foreignId('id_espece')->constrained('especes')->onDelete('cascade');
            $table->unique(['id_nourriture', 'id_espece']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nourriture_espece');
    }
};