@extends('layouts.main')

@section('content')
    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <h1 class="text-white">Tauler</h1>
                <p class="text-white">Benvingut, a KANTUM {{ auth()->user()->email }}</p>

                @php
                    $projects = \App\Models\Projects::latest()->get();
                    $tasks = \App\Models\Tasques::query()->get();
                    $summaryTotalTasks = $tasks->count();
                    $summaryCompletedTasks = $tasks->where('progres', 100)->count();
                    $summaryProgress = $summaryTotalTasks > 0
                        ? round($tasks->avg('progres'))
                        : 0;
                @endphp

                <div class="card dashboard-card dashboard-summary-card bg-secondary text-white rounded-4 p-4">
                    <div class="row flex-grow-1 align-items-center g-4 px-4">
                        <div class="col-12 col-md-5">
                            <h3>Resumen</h3>
                            <p class="text-secondary fs-5 mb-0">Estadísticas generales de todos los proyectos</p>
                        </div>

                        <div class="col-12 col-md-7 d-flex align-items-center justify-content-md-end gap-4">
                            <div class="dashboard-progress-circle" role="progressbar"
                                aria-label="Progreso general del proyecto"
                                aria-valuenow="{{ $summaryProgress }}" aria-valuemin="0" aria-valuemax="100">

                                <svg viewBox="0 0 42 42" aria-hidden="true">
                                    <circle class="dashboard-progress-circle-track" cx="21" cy="21" r="15.9155"></circle>
                                    <circle class="dashboard-progress-circle-value" cx="21" cy="21" r="15.9155"
                                        stroke-dasharray="{{ $summaryProgress }} {{ 100 - $summaryProgress }}"></circle>
                                </svg>
                                <span>{{ $summaryProgress }}%</span>
                            </div>

                            <div>
                                <h3 class="text-white mb-1">Tareas totales</h3>
                                <p class="text-secondary fs-5 mb-0">
                                    {{ $summaryCompletedTasks }} de {{ $summaryTotalTasks }} tareas
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="dashboard-section-divider my-4">

                <div class="row g-4 mt-5">
                    @foreach ($projects as $project)
                        @php
                            $projectTasks = $tasks->where('projecte_id', $project->id);
                            $completedTasks = $projectTasks->where('progres', 100)->count();
                            $projectProgress = $projectTasks->count() > 0
                                ? round($projectTasks->avg('progres'))
                                : 0;
                        @endphp

                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card dashboard-project-card border-0 bg-secondary text-white rounded-4 overflow-hidden h-100"
                                style="--project-color: {{ $project->color }};">
                                <div class="dashboard-project-header p-4" style="background-color: {{ $project->color }};">
                                    <h5 class="mb-1 fw-bold text-white">{{ $project->nom }}</h5>
                                    <small class="fw-bold text-white">
                                        Creado el {{ $project->created_at->format('d/m/Y') }}
                                    </small>
                                </div>

                                <div class="p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small text-secondary">Progreso</span>
                                        <span class="small text-secondary">{{ $projectProgress }}%</span>
                                    </div>
                                    <div class="progress mb-4" role="progressbar"
                                        aria-label="Progreso de {{ $project->nom }}"
                                        aria-valuenow="{{ $projectProgress }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar"
                                            style="width: {{ $projectProgress }}%; background-color: {{ $project->color }};"></div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Tareas</h6>
                                        <span class="small text-secondary">
                                            {{ $completedTasks }} / {{ $projectTasks->count() }}
                                        </span>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @forelse ($projectTasks as $task)
                                            <li class="list-group-item bg-transparent text-white px-0">
                                                {{ $task->nom }}
                                            </li>
                                        @empty
                                            <li class="list-group-item bg-transparent text-secondary px-0">
                                                No hay tareas
                                            </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                        <button type="button"
                                class="col-xl-4 border-0 bg-transparent dashboard-create-project"
                                data-bs-toggle="modal"
                                data-bs-target="#crearModal">

                            <div class="card dashboard-card dashboard-create-project-card bg-secondary text-white rounded-4 h-100">
                                <span class="dashboard-create-project-plus">+</span>
                            </div>

                        </button>
                </div>
            </main>

        </div>
    </div>

    <div class="modal" id="crearModal" tabindex="-1" aria-labelledby="crearModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-secondary text-white">

                <div class="modal-header">
                    <h5 class="modal-title" id="crearModalLabel">Crear proyecto</h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf

                    <div class="modal-body">
                        <div>
                            <label for="project-name" class="form-label">Nombre</label>
                            <input type="text" id="project-name" name="nom"
                                class="form-control" placeholder="Nombre del proyecto" required>
                        </div>

                        <div class="mt-3">
                            <label for="project-description" class="form-label">Descripción</label>
                            <textarea id="project-description" name="descripcion"
                                class="form-control" rows="3"
                                placeholder="Describe brevemente el proyecto"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light"
                                data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar proyecto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection