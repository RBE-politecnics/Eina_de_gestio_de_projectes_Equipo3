<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController
{
    public function store(Request $request): RedirectResponse
    {
        $project = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
        ]);

        Projects::create($project);

        return redirect()->route('dashboard')->with('status', 'Proyecto creado correctamente.');
    }
}
