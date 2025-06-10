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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('idpromotion');
            $table->string('codepromotion', 50)->unique();
            $table->float('valeurreduction');
            $table->timestamps();

            $table->foreignId('idevenement')
                  ->constrained('evenements', 'idevenement')
                  ->onDelete('cascade'); 
            $table->softDeletes(); // Ajout de la colonne deleted_at pour le soft delete
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
