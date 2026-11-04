<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController
{
    public function index(): View
    {
        return view('projects.index', [
            'projects' => Projects::latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $project = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
        ]);

        Projects::create($project);

        if ($request->input('source') === 'dashboard') {
            return redirect()->route('dashboard')->with('status', 'Proyecto creado correctamente.');
        }

        return redirect()->route('projects.index')->with('status', 'Proyecto creado correctamente.');
    }

    public function show(Projects $project): View
    {
        return view('projects.show', compact('project'));
    }

    public function edit(Projects $project): View
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Projects $project): RedirectResponse
    {
        $projectData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $project->update($projectData);

        return redirect()->route('projects.index')->with('status', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Projects $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')->with('status', 'Proyecto eliminado correctamente.');
    }
}
