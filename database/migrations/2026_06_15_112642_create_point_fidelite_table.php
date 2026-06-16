<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_fidelite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->decimal('montant_points', 12, 2);
            $table->enum('type_operation', ['gain', 'depense']);
            $table->timestamp('date_operation')->useCurrent();
            $table->foreignId('id_transaction_source')->nullable()->constrained('transaction')->onDelete('set null');
            $table->date('date_expiration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_fidelite');
    }
};