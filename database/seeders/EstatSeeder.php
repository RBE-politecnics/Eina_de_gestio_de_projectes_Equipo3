<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estats')->insert([
            ['nom' => 'Per fer', 'bloqueja' => false],
            ['nom' => 'En progrés', 'bloqueja' => false],
            ['nom' => 'Feta', 'bloqueja' => true],
        ]);
    }
}
