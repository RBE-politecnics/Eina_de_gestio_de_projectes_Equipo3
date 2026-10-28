@extends('layouts.main')

@section('content')
    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <h1 class="text-white">Tauler</h1>
                <p class="text-white">Benvingut a KANTUM</p>

                @php
                    $summaryProgress = 75;
                    $summaryCompletedTasks = 18;
                    $summaryTotalTasks = 24;
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

                <!-- Cartas de proyectos -->
                @php
                    $projects = [
                        [
                            'name' => 'Rediseño web',
                            'description' => 'Nueva interfaz para la plataforma.',
                            'tasks' => '18 / 24',
                            'date' => '12 nov. 2026',
                            'progress' => 75,
                            'color' => 'success',
                        ],
                        [
                            'name' => 'App móvil',
                            'description' => 'Aplicación móvil para equipos.',
                            'tasks' => '9 / 20',
                            'date' => '28 nov. 2026',
                            'progress' => 45,
                            'color' => 'primary',
                        ],
                        [
                            'name' => 'Campaña marketing',
                            'description' => 'Planificación de la campaña anual.',
                            'tasks' => '6 / 15',
                            'date' => '5 dic. 2026',
                            'progress' => 40,
                            'color' => 'warning',
                        ],
                        [
                            'name' => 'Portal cliente',
                            'description' => 'Espacio privado para clientes.',
                            'tasks' => '14 / 18',
                            'date' => '20 nov. 2026',
                            'progress' => 78,
                            'color' => 'info',
                        ],
                        [
                            'name' => 'Automatización',
                            'description' => 'Optimización de procesos internos.',
                            'tasks' => '4 / 16',
                            'date' => '15 dic. 2026',
                            'progress' => 25,
                            'color' => 'danger',
                        ],
                    ];
                @endphp

                <div class="row g-4 mt-5">
                    @foreach ($projects as $project)
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card dashboard-card bg-secondary text-white rounded-4 p-4 h-100 border-{{ $project['color'] }}">
                                <h5>{{ $project['name'] }}</h5>
                                <p class="text-secondary">{{ $project['description'] }}</p>

                                <div class="d-flex justify-content-between">
                                    <span class="text-secondary">Tareas: {{ $project['tasks'] }}</span>
                                    <span class="text-secondary">{{ $project['date'] }}</span>
                                </div>

                                <p class="small text-secondary mb-1">PROGRÉS</p>
                                <div class="progress" style="height: 7px;" role="progressbar" aria-label="Progreso de {{ $project['name'] }}" aria-valuenow="{{ $project['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-{{ $project['color'] }}" style="width: {{ $project['progress'] }}%;"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </main>

        </div>
    </div>
@endsection