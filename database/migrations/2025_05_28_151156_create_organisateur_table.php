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
        Schema::create('organisateur', function (Blueprint $table) {
            $table->id('idOrganisateur');
            $table->string('societe');
            $table->string('secteurActivite');
            $table->string('ICE');
            $table->string('nomGerant');
            $table->string('prenomGerant');
            $table->boolean('estActif')->default(false);

            // Clé étrangère vers users
            $table->foreignId('user_id')
                  ->constrained('users', 'id')
                  ->onDelete('cascade');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisateur');
    }
};
