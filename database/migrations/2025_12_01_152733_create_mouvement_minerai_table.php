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
        Schema::create('vercorium.Mouvement_minerai', function (Blueprint $table) {
            $table->id('id_mouvement');
            $table->date('date_mouvement')->nullable();
            $table->string('type_mouvement', 50)->nullable();
            $table->decimal('quantite', 10, 2)->nullable();

            $table->foreignId('id_site')->constrained('vercorium.Site', 'id_site');
            $table->foreignId('id')->constrained('vercorium.users', 'id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_minerai');
    }
};
