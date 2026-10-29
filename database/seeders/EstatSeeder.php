<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstatSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estats')->insert([
            ['nom' => 'Pendent'],
            ['nom' => 'En curs'],
            ['nom' => 'Completada'],
        ]);
    }
}
