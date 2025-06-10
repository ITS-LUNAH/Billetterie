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
        Schema::create('zone_tarifaire', function (Blueprint $table) {
            $table->id('idzone');
            $table->string('nomzone', 100);
            $table->float('tarif');
            $table->integer('capacite');
            $table->string('couleur', 50)->nullable(); // Couleur de la zone tarifaire, peut être null
            $table->timestamps();

            $table->foreignId('idlieu') 
                  ->constrained('lieu_evenements', 'idlieu')
                  ->onDelete('cascade');
            $table->softDeletes(); // Ajout de la colonne deleted_at pour le soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_tarifaire');
    }
};
