<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Participant;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $participantUser = User::create([
            'typeUser' => 'Participant', 
            'firstName' => 'user',
            'lastName' => '2',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456789'), // Always hash passwords for security!
        ]);

        Participant::create([
            'user_id' => $participantUser->id,
            'compteBancaire' => 'CIH',
            'IBAN' => '2222',
            'RIB' => '2222',
        ]);
    }
}
