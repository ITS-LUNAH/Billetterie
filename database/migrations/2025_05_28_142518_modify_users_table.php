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
        //
        Schema::table('users', function (Blueprint $table) {
            // Suppression de la colonne 'name' 
            $table->dropColumn('name');
            //ajouts des nouvelles colonnes
            $table->enum('typeUser', ['Administrateur', 'Organisateur', 'Participant'])
                ->default('Participant')
                ->after('id');
            $table->string('firstName')->after('TypeUser')->after('TypeUser');
            $table->string('lastName')->after('firstName')->after('firstName');
            $table->string('telphone')->nullable()->unique()->after('lastName');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function (Blueprint $table) {
            // Annuler les modifications
            $table->dropColumn([
                'typeUser',
                'firstName',
                'lastName',
                'telphone']);
            // Recréer la colonne 'name'
            $table->string('name');
        });
    }
};
