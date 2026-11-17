<?php

namespace App\Http\Controllers;

use App\Models\Tasques;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TasquesController
{
    public function store(Request $request): RedirectResponse
    {
        $tasca = $request->validate([
            'projecte_id' => ['required', 'integer', 'exists:projects,id'],
            'nom' => ['required', 'string', 'max:255'],
            'data_finalitzacio' => ['nullable', 'date'],
            'estats_id' => ['required', 'integer', 'exists:estats,id'],
            'comentaris' => ['nullable', 'string'],
            'progres' => ['nullable', 'integer', 'between:0,100'],
            'color' => ['nullable', 'string', 'max:255'],
        ]);

        $tasca['users_id'] = $request->user()->id;

        Tasques::create($tasca);

        return redirect()->route('dashboard')->with('status', 'Tasca creada correctament.');
    }
}
