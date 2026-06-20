<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commande', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_acheteur')->constrained('users')->onDelete('restrict');
            $table->foreignId('id_vendeur')->constrained('users')->onDelete('restrict');
            $table->foreignId('id_annonce')->constrained('annonce')->onDelete('restrict');
            $table->foreignId('id_transporteur')->nullable()->constrained('users')->onDelete('set null')->comment('Transport optionnel');
            $table->decimal('prix_unitaire', 12, 2)->comment('Prix unitaire du produit');
            $table->decimal('quantite', 10, 2)->default(1)->comment('Quantité commandée');
            $table->decimal('reduction', 12, 2)->default(0)->comment('Réduction accordée par le vendeur');
            $table->decimal('montant_total', 14, 2)->comment('Montant total avant réduction');
            $table->decimal('montant_ajuste', 14, 2)->comment('Montant total après réduction (montant_total - reduction)');
            $table->decimal('commission_prelevee', 14, 2)->default(0);
            $table->timestamp('date_commande')->useCurrent();
            $table->enum('statut_commande', ['en_attente', 'validee', 'livree', 'annulee'])->default('en_attente');
            $table->text('avis_client')->nullable()->comment('Avis laissé par l\'acheteur');
            $table->tinyInteger('note')->unsigned()->nullable()->comment('Note en étoiles 1-5');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande');
    }
};