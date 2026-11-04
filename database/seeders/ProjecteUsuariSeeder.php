<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjecteUsuariSeeder extends Seeder
{
    public function run(): void
    {
        $projecteIds = DB::table('projects')
            ->whereIn('nom', ['melapela', 'nomelapela'])
            ->pluck('id', 'nom');
        $usuariId = DB::table('users')
            ->where('email', 'superadmin@gmail.com')
            ->value('id');
        $rolId = DB::table('rols')->where('nom', 'Administrador')->value('id');

        DB::table('projecte__usuaris')->insert([
            [
                'projecte_id' => $projecteIds['melapela'],
                'users_id' => $usuariId,
                'rols_id' => $rolId,
            ],
            [
                'projecte_id' => $projecteIds['nomelapela'],
                'users_id' => $usuariId,
                'rols_id' => $rolId,
            ],
        ]);
    }
}
