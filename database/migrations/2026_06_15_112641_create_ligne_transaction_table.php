<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ligne_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaction')->constrained('transaction')->onDelete('cascade');
            $table->foreignId('id_annonce')->constrained('annonce')->onDelete('restrict');
            $table->decimal('quantite', 10, 2)->default(1);
            $table->decimal('prix_unitaire', 12, 2);
            $table->decimal('montant_ligne', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ligne_transaction');
    }
};