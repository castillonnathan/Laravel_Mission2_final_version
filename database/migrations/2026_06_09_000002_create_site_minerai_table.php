<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Table pivot : liste des minerais autorisés sur un site.
     *
     * Si un site n'a aucune ligne dans cette table → aucune restriction
     * (comportement rétrocompatible pour les dépôts/autres sites).
     *
     * Si un site possède au moins une ligne → seuls les minerais listés
     * sont acceptés en source ou en destination de mouvement.
     */
    public function up(): void
    {
        Schema::create('site_minerai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('minerai_id')->constrained('minerais')->cascadeOnDelete();
            $table->timestamps();

            // Un minerai ne peut être autorisé qu'une seule fois par site
            $table->unique(['site_id', 'minerai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_minerai');
    }
};
