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
        Schema::table('billets', function (Blueprint $table) {
            //
            $table->foreignId('iddate') 
                  ->constrained('date_evenements', 'iddate')
                  ->after('idreservation')
                  ->nullable()
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billets', function (Blueprint $table) {
            //
            $table->dropForeign(['iddate']);
            $table->dropColumn('iddate');
        });
    }
};
