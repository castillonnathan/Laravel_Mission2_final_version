<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minerais', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();   // FE, CU, AU…
            $table->string('nom');                  // Fer, Cuivre, Or…
            $table->string('unite', 20)->default('tonne'); // tonne, kg, m3
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minerais');
    }
};
