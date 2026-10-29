<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rols')->insert([
            ['nom' => 'Administrador'],
            ['nom' => 'Col·laborador'],
        ]);
    }
}
