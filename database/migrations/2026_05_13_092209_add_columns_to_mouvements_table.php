<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mouvements', function (Blueprint $table) {
            $table->string('numero', 30)->unique()->after('id');
            $table->enum('type', ['entree', 'sortie', 'transfert', 'ajustement'])->after('numero');
            $table->foreignId('minerai_id')->constrained('minerais')->after('type');
            $table->decimal('quantite', 15, 3)->after('minerai_id');
            $table->foreignId('site_source_id')->nullable()->constrained('sites')->nullOnDelete()->after('quantite');
            $table->foreignId('site_destination_id')->nullable()->constrained('sites')->nullOnDelete()->after('site_source_id');
            $table->text('motif')->nullable()->after('site_destination_id');
            $table->timestamp('date_mouvement')->useCurrent()->after('motif');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('date_mouvement');

            $table->index('type');
            $table->index('date_mouvement');
        });
    }

    public function down(): void
    {
        Schema::table('mouvements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('minerai_id');
            $table->dropConstrainedForeignId('site_source_id');
            $table->dropConstrainedForeignId('site_destination_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['numero', 'type', 'quantite', 'motif', 'date_mouvement']);
        });
    }
};
