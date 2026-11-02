<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Users')->insert([
            [
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('SuperAdmin'),
            ],
            [
                'email' => 'Usuari de prova 1',
                'password' => Hash::make('password'),
            ],
            [
                'email' => 'Usuari de prova 2',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
