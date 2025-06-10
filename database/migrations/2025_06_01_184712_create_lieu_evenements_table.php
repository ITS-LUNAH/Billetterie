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
        Schema::create('lieu_evenements', function (Blueprint $table) {
            $table->id('idlieu');//clé primaire
            $table->string('nom', 100); // Nom du lieu, unique
            $table->string('ville',100);
            $table->string('adresse',255);
            $table->integer('capaciteMax');
            $table->timestamps();
            $table->softDeletes(); // Ajout de la colonne deleted_at pour le soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lieu_evenements');
    }
};
