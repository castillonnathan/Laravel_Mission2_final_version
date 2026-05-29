<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vercorium.Materiel', function (Blueprint $table) {
            $table->id('id_materiel');
            $table->string('nom', 150)->nullable();
            $table->text('description')->nullable();
            $table->integer('quantite_stock')->nullable();
            $table->integer('seuil_alerte')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiel');
    }
};
