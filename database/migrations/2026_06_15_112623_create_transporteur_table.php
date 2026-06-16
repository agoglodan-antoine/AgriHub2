<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transporteur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->unique()->constrained('users')->onDelete('cascade');
            $table->string('type_vehicule', 100)->nullable();
            $table->decimal('capacite_transport', 10, 2)->nullable();
            $table->string('zone_intervention', 200)->nullable();
            $table->string('licence_transport', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transporteur');
    }
};