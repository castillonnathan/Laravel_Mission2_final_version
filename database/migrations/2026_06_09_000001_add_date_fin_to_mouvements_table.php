<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute :
     *  - statut_transfert : null pour les non-transferts,
     *                       'en_cours' à la création d'un transfert,
     *                       'termine'  quand un admin/technicien le clôture.
     *  - date_fin         : horodatage automatique du passage à 'termine'.
     */
    public function up(): void
    {
        Schema::table('mouvements', function (Blueprint $table) {
            $table->enum('statut_transfert', ['en_cours', 'termine'])
                  ->nullable()
                  ->after('date_mouvement')
                  ->comment('Uniquement renseigné pour les mouvements de type transfert');

            $table->timestamp('date_fin')
                  ->nullable()
                  ->after('statut_transfert')
                  ->comment('Horodatage de clôture du transfert');
        });
    }

    public function down(): void
    {
        Schema::table('mouvements', function (Blueprint $table) {
            $table->dropColumn(['statut_transfert', 'date_fin']);
        });
    }
};
