@extends('layouts.main')

@section('content')
    @php
        $dies = ['', 'dilluns', 'dimarts', 'dimecres', 'dijous', 'divendres', 'dissabte', 'diumenge'];
        $mesos = ['', 'gener', 'febrer', 'març', 'abril', 'maig', 'juny', 'juliol', 'agost', 'setembre', 'octubre', 'novembre', 'desembre'];
        $avui = $dies[date('N')] . ', ' . date('j') . ' de ' . $mesos[date('n')];

        $nomUsuari = ucfirst(explode('@', auth()->user()->email)[0]);

        // Només els projectes en què participa l'usuari
        $idsProjectes = \App\Models\Projecte_Usuari::where('users_id', auth()->id())->pluck('projecte_id');
        $projects = \App\Models\Projects::whereIn('id', $idsProjectes)->latest()->get();
        $tasks = \App\Models\Tasques::whereIn('projecte_id', $idsProjectes)->get();

        $summaryTotalTasks = $tasks->count();
        $summaryCompletedTasks = $tasks->where('progres', 100)->count();
        $summaryProgress = $summaryTotalTasks > 0 ? round($tasks->avg('progres')) : 0;
    @endphp

    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">

                <div class="kt-capcalera">
                    <div>
                        <p class="kt-data">{{ $avui }}</p>
                        <h1>Bon dia, {{ $nomUsuari }}</h1>
                    </div>
                    <button type="button" class="btn btn-primary px-4 py-2"
                            data-bs-toggle="modal" data-bs-target="#crearModal">
                        + Crear nou projecte
                    </button>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="kt-targeta kt-resum mb-5">
                    <div>
                        <span class="kt-etiqueta-rodona">Resum general</span>
                        <h2 class="fw-bold mt-3">
                            @if ($summaryTotalTasks == 0)
                                Encara no hi ha tasques.
                            @else
                                Tot avança a bon ritme.
                            @endif
                        </h2>
                        <p class="text-secondary mb-0">
                            Has completat {{ $summaryCompletedTasks }} de {{ $summaryTotalTasks }} tasques.
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-4">
                        <div class="dashboard-progress-circle" role="progressbar"
                            aria-label="Progrés general"
                            aria-valuenow="{{ $summaryProgress }}" aria-valuemin="0" aria-valuemax="100">
                            <svg viewBox="0 0 42 42" aria-hidden="true">
                                <circle class="dashboard-progress-circle-track" cx="21" cy="21" r="15.9155"></circle>
                                <circle class="dashboard-progress-circle-value" cx="21" cy="21" r="15.9155"
                                    stroke-dasharray="{{ $summaryProgress }} {{ 100 - $summaryProgress }}"></circle>
                            </svg>
                            <span>{{ $summaryProgress }}%</span>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold mb-1">Progrés total</h3>
                            <p class="text-secondary small mb-0">{{ $summaryCompletedTasks }} de {{ $summaryTotalTasks }} tasques</p>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h2 class="h4 fw-bold mb-1">Projectes actius</h2>
                        <p class="text-secondary small mb-0">Els espais on el teu equip està treballant ara.</p>
                    </div>
                    <a href="{{ route('projectes') }}" class="text-primary small fw-bold text-decoration-none">Veure'ls tots →</a>
                </div>

                <div class="row g-4">
                    @forelse ($projects as $project)
                        @php
                            $projectTasks = $tasks->where('projecte_id', $project->id);
                            $completedTasks = $projectTasks->where('progres', 100)->count();
                            $projectProgress = $projectTasks->count() > 0 ? round($projectTasks->avg('progres')) : 0;
                            $color = ($project->color && $project->color != '#FFFFFF') ? $project->color : '#007BFF';
                        @endphp

                        <div class="col-12 col-md-6 col-xl-4">
                            <a href="{{ route('tauler') }}" class="kt-targeta kt-projecte" style="--projecte-color: {{ $color }};">
                                <span class="kt-icona mb-3">{{ strtoupper(substr($project->nom, 0, 2)) }}</span>
                                <h3 class="h5 fw-bold">{{ $project->nom }}</h3>
                                <p class="text-secondary small">{{ $project->descripcion ?: 'Sense descripció.' }}</p>

                                <p class="text-secondary small mt-auto mb-2">
                                    {{ $completedTasks }} / {{ $projectTasks->count() }} tasques
                                </p>

                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-secondary">PROGRÉS</span>
                                    <span class="fw-bold">{{ $projectProgress }}%</span>
                                </div>
                                <div class="kt-progres mb-3" role="progressbar"
                                    aria-label="Progrés de {{ $project->nom }}"
                                    aria-valuenow="{{ $projectProgress }}" aria-valuemin="0" aria-valuemax="100">
                                    <div style="width: {{ $projectProgress }}%;"></div>
                                </div>

                                <span class="small fw-bold" style="color: {{ $color }};">Obrir projecte →</span>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="kt-targeta text-center text-secondary">
                                Encara no tens cap projecte. Crea'n un amb el botó de dalt.
                            </div>
                        </div>
                    @endforelse
                </div>
            </main>

        </div>
    </div>

    <div class="modal" id="crearModal" tabindex="-1" aria-labelledby="crearModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-secondary text-white">

                <div class="modal-header">
                    <h5 class="modal-title" id="crearModalLabel">Crear projecte</h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Tancar"></button>
                </div>

                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf

                    <div class="modal-body">
                        <div>
                            <label for="project-name" class="form-label">Nom</label>
                            <input type="text" id="project-name" name="nom"
                                class="form-control" placeholder="Nom del projecte" required>
                        </div>

                        <div class="mt-3">
                            <label for="project-description" class="form-label">Descripció</label>
                            <textarea id="project-description" name="descripcion"
                                class="form-control" rows="3"
                                placeholder="Descriu breument el projecte"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light"
                                data-bs-dismiss="modal">Tancar</button>
                        <button type="submit" class="btn btn-primary">Desar projecte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
