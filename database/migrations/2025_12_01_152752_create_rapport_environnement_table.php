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
        Schema::create('vercorium.Rapport_environnement', function (Blueprint $table) {
            $table->id('id_rapport');
            $table->string('titre', 255)->nullable();
            $table->string('periode', 50)->nullable();
            $table->string('fichier', 255)->nullable();
            $table->date('date_publication')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_environnement');
    }
};
