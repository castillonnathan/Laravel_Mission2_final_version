<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('minerais', function (Blueprint $table) {
            $table->string('code', 20)->unique()->after('id');
            $table->string('nom')->after('code');
            $table->string('unite', 20)->default('tonne')->after('nom');
            $table->text('description')->nullable()->after('unite');
            $table->boolean('actif')->default(true)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('minerais', function (Blueprint $table) {
            $table->dropColumn(['code', 'nom', 'unite', 'description', 'actif']);
        });
    }
};
