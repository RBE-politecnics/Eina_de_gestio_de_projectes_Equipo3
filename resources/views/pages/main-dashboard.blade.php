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
                            $latestTask = $projectTasks->sortByDesc('data_finalitzacio')->first();
                        @endphp

                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card dashboard-card bg-secondary text-white rounded-4 p-4 h-100"
                                style="border-color: {{ $project->color }};">
                                <div class="d-flex align-items-center gap-2">
                                    <h5 class="mb-0">{{ $project->nom }}</h5>
                                </div>
                                <p class="text-secondary mt-3 mb-2">
                                    {{ $latestTask?->comentaris ?? 'Sense comentaris' }}
                                </p>
                                <div class="d-flex justify-content-between">
                                    <span class="text-secondary">
                                        Tasques: {{ $completedTasks }} / {{ $projectTasks->count() }}
                                    </span>
                                    <span class="text-secondary">
                                        {{ $latestTask?->data_finalitzacio ?? 'Sense data' }}
                                    </span>
                                </div>
                                <p class="small text-secondary mb-1 mt-3">PROGRÉS</p>
                                <div class="progress" role="progressbar"
                                aria-label="Progreso de {{ $project->nom }}"
                                    aria-valuenow="{{ $projectProgress }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar"
                                        style="width: {{ $projectProgress }}%; background-color: {{ $project->color }};"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </main>

        </div>
    </div>
@endsection