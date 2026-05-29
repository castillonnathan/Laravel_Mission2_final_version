<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks_minerai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('minerai_id')->constrained('minerais')->cascadeOnDelete();
            $table->decimal('quantite', 15, 3)->default(0);
            $table->timestamps();

            $table->unique(['site_id', 'minerai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks_minerai');
    }
};
