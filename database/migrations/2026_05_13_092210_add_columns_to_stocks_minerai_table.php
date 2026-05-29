<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stocks_minerai', function (Blueprint $table) {
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete()->after('id');
            $table->foreignId('minerai_id')->constrained('minerais')->cascadeOnDelete()->after('site_id');
            $table->decimal('quantite', 15, 3)->default(0)->after('minerai_id');

            $table->unique(['site_id', 'minerai_id']);
        });
    }

    public function down(): void
    {
        Schema::table('stocks_minerai', function (Blueprint $table) {
            $table->dropConstrainedForeignId('site_id');
            $table->dropConstrainedForeignId('minerai_id');
            $table->dropColumn('quantite');
        });
    }
};
