<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Users')->insert([
            ['nom' => 'Usuari de prova 1'],
            ['nom' => 'Usuari de prova 2'],
        ]);
    }
}
