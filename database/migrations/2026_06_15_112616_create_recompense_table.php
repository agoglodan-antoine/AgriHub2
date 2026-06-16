<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recompense', function (Blueprint $table) {
            $table->id();
            $table->string('nom_recompense', 200);
            $table->text('description')->nullable();
            $table->decimal('cout_points', 12, 2);
            $table->string('type_recompense', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recompense');
    }
};