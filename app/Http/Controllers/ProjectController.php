<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\Rols;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController
{
    public function store(Request $request): RedirectResponse
    {
        $project = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
        ]);

        $nouProjecte = Projects::create($project);

        // Qui crea el projecte n'és l'administrador
        DB::table('projecte__usuaris')->insert([
            'projecte_id' => $nouProjecte->id,
            'users_id' => $request->user()->id,
            'rols_id' => Rols::where('nom', 'Administrador')->value('id') ?? DB::table('rols')->insertGetId(['nom' => 'Administrador']),
        ]);

        return redirect()->route('dashboard')->with('status', 'Projecte creat correctament.');
    }
}
