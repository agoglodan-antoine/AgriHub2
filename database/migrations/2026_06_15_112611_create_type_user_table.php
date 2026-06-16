<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_user', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->unique()->comment('Clé machine : eleveur, acheteur, veterinaire...');
            $table->string('label', 100)->comment('Libellé affiché');
            $table->text('description')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_user');
    }
};