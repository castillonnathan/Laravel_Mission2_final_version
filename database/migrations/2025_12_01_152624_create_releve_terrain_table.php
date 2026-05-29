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
        Schema::create('vercorium.releve_terrain', function (Blueprint $table) {
            $table->id('id_releve');
            $table->date('date_releve')->nullable();
            $table->decimal('profondeur', 10, 2)->nullable();
            $table->text('observations')->nullable();
            $table->text('anomalies')->nullable();

            $table->foreignId('id_site')->constrained('vercorium.Site', 'id_site');
            $table->foreignId('id')->constrained('vercorium.users', 'id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releve_terrain');
    }
};
