<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 30)->unique();   // MVT-2026-00001
            $table->enum('type', ['entree', 'sortie', 'transfert', 'ajustement']);

            $table->foreignId('minerai_id')->constrained('minerais');
            $table->decimal('quantite', 15, 3); // peut être négatif pour ajustement

            // Selon le type :
            // - entree     : source NULL, destination = site qui reçoit
            // - sortie     : source = site qui expédie, destination NULL
            // - transfert  : source ET destination requis
            // - ajustement : destination = site concerné, source NULL
            $table->foreignId('site_source_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('site_destination_id')->nullable()->constrained('sites')->nullOnDelete();

            $table->text('motif')->nullable();        // surtout pour ajustement
            $table->timestamp('date_mouvement')->useCurrent();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('type');
            $table->index('date_mouvement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements');
    }
};
