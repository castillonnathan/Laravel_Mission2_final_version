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
        Schema::create('vercorium.Mesure_capteur', function (Blueprint $table) {
            $table->id('id_mesure');
            $table->timestamp('date_mesure')->nullable();
            $table->decimal('valeur', 10, 2)->nullable();
            $table->string('unite', 20)->nullable();

            $table->foreignId('id_capteur')->constrained('vercorium.Capteur', 'id_capteur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesure_capteur');
    }
};
