<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domaine', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('label', 150);
            $table->string('icone', 100)->nullable()->comment('Nom icône ou URL');
            $table->text('description')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domaine');
    }
};