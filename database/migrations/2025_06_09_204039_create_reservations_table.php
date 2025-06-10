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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('idreservation'); // Clé primaire
            $table->dateTime('datereservation');
            $table->string('etat', 50)->default('en attente'); // État de la réservation, par défaut 'en attente'
            $table->timestamps();

            $table->foreignId('idparticipant') 
                  ->constrained('participant', 'idparticipant')
                  ->onDelete('cascade');
            $table->softDeletes(); // Ajout de la colonne deleted_at pour le soft delete

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
