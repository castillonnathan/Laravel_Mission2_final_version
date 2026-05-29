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
        Schema::create('vercorium.Mouvement_stock', function (Blueprint $table) {
            $table->id('id_stock');
            $table->string('type_mouvement', 50)->nullable();
            $table->integer('quantite')->nullable();
            $table->date('date_mouvement')->nullable();

            $table->foreignId('id_materiel')->constrained('vercorium.Materiel', 'id_materiel');
            $table->foreignId('id')->constrained('vercorium.users', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stock');
    }
};
