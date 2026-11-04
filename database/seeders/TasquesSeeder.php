<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasquesSeeder extends Seeder
{
    public function run(): void
    {
        $projecteId = DB::table('projects')->orderBy('id')->value('id');
        $usuariId = DB::table('users')->orderBy('id')->value('id');
        $estatId = DB::table('estats')->where('nom', 'En curs')->value('id');

        DB::table('tasques')->insert([
            'projecte_id' => $projecteId,
            'users_id' => $usuariId,
            'nom' => 'Preparar entorn de proves',
            'data_finalitzacio' => now()->addDays(7)->toDateString(),
            'estats_id' => $estatId,
            'comentaris' => 'Tasques creada pel seeder.',
            'progres' => 25,
            'color' => '#3498DB',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
