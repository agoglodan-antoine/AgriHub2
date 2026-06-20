<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_commande')->constrained('commande')->onDelete('cascade');
            $table->decimal('montant_paye', 14, 2);
            $table->enum('statut_paiement', ['en_attente', 'reussi', 'echoue', 'rembourse'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiement');
    }
};