@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        $projectesDisponibles = \App\Models\Projects::query()->orderBy('nom')->get();
        $estatsDisponibles = \App\Models\Estats::query()->orderBy('id')->get();

        $estats = [
            'per-fer' => 'Per fer',
            'en-progres' => 'En progrés',
            'feta' => 'Feta',
        ];

        $tipus = [
            'disseny' => 'Disseny',
            'frontend' => 'Front-end',
            'backend' => 'Back-end',
            'bd' => 'Base de dades',
            'qa' => 'QA',
        ];

        $tasques = \App\Models\Tasques::query()
            ->with(['estat', 'usuari'])
            ->latest()
            ->get()
            ->map(function (\App\Models\Tasques $tasca): array {
                $estat = strtolower($tasca->estat?->nom ?? '');

                return [
                    'titol' => $tasca->nom,
                    'tipus' => 'backend',
                    'estat' => match ($estat) {
                        'en curs' => 'en-progres',
                        'completada' => 'feta',
                        default => 'per-fer',
                    },
                    'responsable' => $tasca->usuari?->email ?? 'Sense assignar',
                    'data' => $tasca->data_finalitzacio?->format('d/m/Y') ?? 'Sense data',
                    'descripcio' => $tasca->comentaris ?? '',
                ];
            });
    @endphp

    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="text-white">Tauler</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovaTasca">
                        + Nova tasca
                    </button>
                </div>

                <div class="tauler">
                    @foreach ($estats as $clauEstat => $nomEstat)
                        <section class="columna" data-estat="{{ $clauEstat }}" data-nom="{{ $nomEstat }}">
                            <h2 class="h6 text-white">
                                {{ $nomEstat }} <span class="comptador">0</span>
                            </h2>

                            <div class="columna-tasques">
                                @foreach ($tasques as $tasca)
                                    @if ($tasca['estat'] == $clauEstat)
                                        <article class="tasca vora-{{ $tasca['tipus'] }} {{ $clauEstat == 'feta' ? 'bloquejada' : '' }}"
                                            draggable="{{ $clauEstat == 'feta' ? 'false' : 'true' }}"
                                            data-bs-toggle="modal" data-bs-target="#modalTasca"
                                            data-estat="{{ $tasca['estat'] }}"
                                            data-titol="{{ $tasca['titol'] }}"
                                            data-tipus="{{ $tipus[$tasca['tipus']] }}"
                                            data-responsable="{{ $tasca['responsable'] }}"
                                            data-data="{{ $tasca['data'] }}"
                                            data-descripcio="{{ $tasca['descripcio'] }}">
                                            <span class="tipus tipus-{{ $tasca['tipus'] }}">{{ $tipus[$tasca['tipus']] }}</span>
                                            <p class="tasca-titol">{{ $tasca['titol'] }}</p>
                                            <small class="text-secondary">{{ $tasca['data'] }} · {{ $tasca['responsable'] }}</small>
                                        </article>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <p class="small text-secondary mt-3">
                    Arrossega les tasques per canviar-ne l'estat. Les tasques de «Feta» ja no es poden moure.
                </p>
            </main>
        </div>
    </div>

    {{-- Detall de la tasca --}}
    <div class="modal fade" id="modalTasca" tabindex="-1" aria-labelledby="detallTitol" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallTitol"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tancar"></button>
                </div>
                <div class="modal-body">
                    <p id="detallDescripcio"></p>
                    <p class="mb-1"><strong>Tipus:</strong> <span id="detallTipus"></span></p>
                    <p class="mb-1"><strong>Estat:</strong> <span id="detallEstat"></span></p>
                    <p class="mb-1"><strong>Responsable:</strong> <span id="detallResponsable"></span></p>
                    <p class="mb-1"><strong>Data de lliurament:</strong> <span id="detallData"></span></p>
                    <p id="detallBloqueig" class="small missatge-bloqueig mt-3 d-none">
                        Aquesta tasca està feta i ja no es pot canviar d'estat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulari de tasca nova --}}
    <div class="modal fade" id="modalNovaTasca" tabindex="-1" aria-labelledby="novaTascaTitol" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="novaTascaTitol">Nova tasca</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tancar"></button>
                </div>

                <form id="formNovaTasca" method="POST" action="{{ route('tasques.store') }}">
                    @csrf
                    <div class="modal-body">
                        <label for="novaProjecte" class="form-label">Projecte</label>
                        <select id="novaProjecte" name="projecte_id" class="form-select mb-3" required>
                            <option value="">Selecciona un projecte</option>
                            @foreach ($projectesDisponibles as $projecte)
                                <option value="{{ $projecte->id }}">{{ $projecte->nom }}</option>
                            @endforeach
                        </select>

                        <label for="novaTitol" class="form-label">Títol</label>
                        <input id="novaTitol" name="nom" type="text" class="form-control mb-3" required>

                        <label for="novaDescripcio" class="form-label">Descripció</label>
                        <textarea id="novaDescripcio" name="comentaris" class="form-control mb-3" rows="2"></textarea>

                        <label for="novaEstat" class="form-label">Estat</label>
                        <select id="novaEstat" name="estats_id" class="form-select mb-3" required>
                            <option value="">Selecciona un estat</option>
                            @foreach ($estatsDisponibles as $estat)
                                <option value="{{ $estat->id }}">{{ $estat->nom }}</option>
                            @endforeach
                        </select>

                        <label for="novaData" class="form-label">Data de lliurament</label>
                        <input id="novaData" name="data_finalitzacio" type="date" class="form-control">

                        <input type="hidden" name="progres" value="0">
                        <input type="hidden" name="color" value="#FFFFFF">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel·lar</button>
                        <button type="submit" class="btn btn-primary">Crear tasca</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/tauler.js') }}"></script>
@endsection
