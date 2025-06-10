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
        Schema::create('remboursements', function (Blueprint $table) {
            $table->id('idremboursement');
            $table->float('montantremboursement', 10, 2)->nullable();
            $table->date('dateremboursement')->nullable();
            $table->enum('statut', ['en_attente', 'effectué', 'rejeté'])->default('en_attente');
            $table->string('motifremboursement', 255)->nullable();
            $table->timestamps();

            $table->foreignId('idParticipant')
                  ->constrained('participant', 'idParticipant')
                  ->onDelete('cascade'); // Lien avec la table commandes
            $table->softDeletes(); // Ajout de la colonne deleted_at pour le soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remboursements');
    }
};
