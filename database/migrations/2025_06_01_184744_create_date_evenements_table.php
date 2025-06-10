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
        Schema::create('date_evenements', function (Blueprint $table) {
            $table->id('iddate');//clé primaire
            $table->dateTime('datedebut'); // Date de début de l'événement
            $table->time('heuredebut');
            $table->time('heureouvertureportes');
            $table->integer('placesdisponibles'); // Nombre de places disponibles
            $table->integer('placesvendues')->default(0); // Nombre de places vendues, initialisé à 0
            
            // Clé étrangère
            $table->foreignId('idevenement')
                  ->constrained('evenements', 'idevenement')
                  ->onDelete('cascade');
            $table->foreignId('idlieu')
                  ->constrained('lieu_evenements', 'idlieu')
                  ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); // Ajout de la colonne deleted_at pour le soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date_evenements');
    }
};
