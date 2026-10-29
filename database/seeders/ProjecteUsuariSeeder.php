<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjecteUsuariSeeder extends Seeder
{
    public function run(): void
    {
        $projecteId = DB::table('projects')->orderBy('id')->value('id');
        $usuariId = DB::table('Users')->orderBy('id')->value('id');
        $rolId = DB::table('rols')->where('nom', 'Administrador')->value('id');

        DB::table('projecte__usuaris')->insert([
            'projecte_id' => $projecteId,
            'users_id' => $usuariId,
            'rols_id' => $rolId,
        ]);
    }
}
