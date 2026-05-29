<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprime les FK qui pointent vers Site avant de la dropper
        DB::statement('ALTER TABLE "releve_terrain" DROP CONSTRAINT IF EXISTS "vercorium_releve_terrain_id_site_foreign"');
        DB::statement('ALTER TABLE "Capteur" DROP CONSTRAINT IF EXISTS "vercorium_capteur_id_site_foreign"');

        // Supprime l'ancienne table CamelCase
        DB::statement('DROP TABLE IF EXISTS "Site" CASCADE');

        // Ajoute les colonnes manquantes à la table 'sites'
        Schema::table('sites', function (Blueprint $table) {
            $table->string('nom')->after('id');
            $table->enum('type', ['mine', 'depot', 'client_site', 'autre'])->default('depot')->after('nom');
            $table->string('adresse')->nullable()->after('type');
            $table->string('ville')->nullable()->after('adresse');
            $table->string('code_postal', 20)->nullable()->after('ville');
            $table->string('pays')->nullable()->after('code_postal');
            $table->text('notes')->nullable()->after('pays');
            $table->boolean('actif')->default(true)->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['nom', 'type', 'adresse', 'ville', 'code_postal', 'pays', 'notes', 'actif']);
        });
    }
};
