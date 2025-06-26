<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->unsignedBigInteger('idevenement')->nullable()->after('idreservation');

        // Optionnel : clé étrangère
        $table->foreign('idevenement')->references('idevenement')->on('evenements')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropForeign(['idevenement']);
        $table->dropColumn('idevenement');
    });
}

};
