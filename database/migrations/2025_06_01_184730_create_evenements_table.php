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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id('idevenement'); // Clé primaire
            $table->string('titre', 255);
            $table->text('description');
            $table->string('lieu',255);
            $table->string('bandeannonce', 255);
            $table->string('categorie', 50);
            $table->boolean('estvalide')->default(false); // Indique si l'événement est validé
            
            // clée étrangère vers l'organisateur
            $table->foreignId('idorganisateur') 
                  ->constrained('organisateur', 'idOrganisateur')
                  ->onDelete('cascade'); 
            $table->float('prixbase');
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
