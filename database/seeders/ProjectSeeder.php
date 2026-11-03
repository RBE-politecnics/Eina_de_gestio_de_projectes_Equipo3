<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'nom' => 'melapela',
                'Color' => '#198754',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'nomelapela',
                'Color' => '#0d6efd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
