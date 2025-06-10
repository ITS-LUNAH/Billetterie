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
        Schema::create('billets', function (Blueprint $table) {
            $table->id('idbillet'); // Clé primaire
            $table->boolean('estscanne')->default(false); // Indique si le billet est scanné, par défaut false
            $table->dateTime('datevalidation')->default(null)->nullable(); // Date de validation du billet, par défaut null
            $table->string('QRcode', 255)->unique();
            $table->timestamps();

            $table->foreignId('idevenement') 
                  ->constrained('evenements', 'idevenement')
                  ->onDelete('cascade');
            $table->foreignId('idparticipant') 
                  ->constrained('participant', 'idparticipant')
                  ->onDelete('cascade');
            $table->foreignId('idreservation') 
                  ->constrained('reservations', 'idreservation')
                  ->onDelete('cascade');
            $table->softDeletes(); // Ajout de la colonne deleted_at pour le soft delete

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billets');
    }
};
