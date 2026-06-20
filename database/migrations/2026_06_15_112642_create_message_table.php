<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_expediteur')->constrained('users')->onDelete('restrict');
            $table->foreignId('id_destinataire')->constrained('users')->onDelete('restrict');
            $table->foreignId('id_annonce')->nullable()->constrained('annonce')->onDelete('set null');
            $table->foreignId('id_commande')->nullable()->constrained('commande')->onDelete('set null');
            $table->text('contenu');
            $table->timestamp('date_envoi')->useCurrent();
            $table->boolean('est_reponse')->default(false)->comment('Indique si le message est une réponse');
            $table->foreignId('reponse_a_id')->nullable()->constrained('message')->onDelete('set null');
            $table->boolean('lu')->default(false);
            $table->boolean('has_pieces_jointes')->default(false);
            $table->boolean('est_demande_commande')->default(false);
            $table->boolean('est_demande_paiement')->default(false);
            $table->timestamps();

            $table->index(['id_expediteur', 'id_destinataire', 'date_envoi']);
            $table->index('id_annonce');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message');
    }
};