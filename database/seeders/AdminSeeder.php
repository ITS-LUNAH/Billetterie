<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Administrateur;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $adminUser = User::create([
            'typeUser' => 'Administrateur', 
            'firstName' => 'Super',
            'lastName' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'), // passwords haché pour la securité!
        ]);

        Administrateur::create([
            'user_id' => $adminUser->id, // Assign the ID from the newly created User to the 'user_id' foreign key
        ]);
    }
}
