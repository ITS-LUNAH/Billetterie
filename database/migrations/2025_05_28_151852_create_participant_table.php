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
        Schema::create('participant', function (Blueprint $table) {
            $table->id('idParticipant');
            $table->string('compteBancaire')->nullable();
            $table->string('IBAN', 40)->nullable();
            $table->string('RIB', 40)->nullable();
            
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
        Schema::dropIfExists('participant');
    }
};
