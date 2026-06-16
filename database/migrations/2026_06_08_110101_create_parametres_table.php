<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('nom_plateforme', 100);
            $table->string('logo', 255)->nullable();
            $table->string('slogan', 255)->nullable();
            $table->string('mail', 255)->nullable();
            $table->string('tel', 20)->nullable();
            $table->string('bp', 50)->nullable();
            $table->string('departement', 100)->nullable();
            $table->string('commune', 100)->nullable();
            $table->string('arrondissement', 100)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('whatsapp', 255)->nullable();
            $table->string('linkedin', 255)->nullable();
            $table->string('twitter', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('photo_de_vue', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres');
    }
};