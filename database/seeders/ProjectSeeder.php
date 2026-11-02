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
                'nom' => 'Rediseño web',
                'Color' => '#198754',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'App móvil',
                'Color' => '#0d6efd',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
