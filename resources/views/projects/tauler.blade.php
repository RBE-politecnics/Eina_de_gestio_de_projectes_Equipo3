@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        // Dades d'exemple. Quan el backend estigui fet, vindran del controlador.
        $projecte = 'Kantum Web App';

        $estats = [
            ['clau' => 'per-fer', 'nom' => 'Per fer'],
            ['clau' => 'en-progres', 'nom' => 'En progrés'],
            ['clau' => 'feta', 'nom' => 'Feta'],
        ];

        $tipus = [
            'disseny' => 'Disseny',
            'frontend' => 'Front-end',
            'backend' => 'Back-end',
            'bd' => 'Base de dades',
            'qa' => 'QA',
        ];

        $tasques = [
            ['id' => 1, 'titol' => 'Definir estructura de la web', 'tipus' => 'disseny', 'estat' => 'feta', 'responsable' => 'Alex Campano', 'data' => '2026-11-03', 'descripcio' => 'Decidir quines pantalles té l\'aplicació i com es navega entre elles.'],
            ['id' => 2, 'titol' => 'Dissenyar mockups', 'tipus' => 'disseny', 'estat' => 'feta', 'responsable' => 'Marc García', 'data' => '2026-11-04', 'descripcio' => 'Passar el wireframe a un disseny amb els colors del manual.'],
            ['id' => 3, 'titol' => 'Vista del tauler Kanban', 'tipus' => 'frontend', 'estat' => 'en-progres', 'responsable' => 'Alex Campano', 'data' => '2026-11-12', 'descripcio' => 'Columnes per estat i tasques que es poden arrossegar.'],
            ['id' => 4, 'titol' => 'CRUD de tasques', 'tipus' => 'backend', 'estat' => 'en-progres', 'responsable' => 'Roc Burnat', 'data' => '2026-11-14', 'descripcio' => 'Crear, modificar i eliminar tasques des del servidor.'],
            ['id' => 5, 'titol' => 'Taules de tipus de tasca', 'tipus' => 'bd', 'estat' => 'per-fer', 'responsable' => 'Roc Burnat', 'data' => '2026-11-15', 'descripcio' => 'Afegir la taula de tipus amb el nom i el color.'],
            ['id' => 6, 'titol' => 'Vista del calendari', 'tipus' => 'frontend', 'estat' => 'per-fer', 'responsable' => 'Alex Campano', 'data' => '2026-11-18', 'descripcio' => 'Mostrar les dates de lliurament de les tasques en un mes.'],
            ['id' => 7, 'titol' => 'Provar el login i el registre', 'tipus' => 'qa', 'estat' => 'per-fer', 'responsable' => 'Marc García', 'data' => '2026-11-20', 'descripcio' => 'Comprovar que els errors es mostren bé al formulari.'],
        ];
    @endphp

    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                    <div>
                        <h1 class="text-white mb-1">{{ $projecte }}</h1>
                        <p class="text-secondary mb-0">{{ count($tasques) }} tasques · 4 membres</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovaTasca">
                        + Nova tasca
                    </button>
                </div>

                @include('components.menu-projecte')

                <div class="d-flex flex-wrap gap-2 mb-4">
                    @foreach ($tipus as $clau => $nom)
                        <span class="tipus tipus-{{ $clau }}">{{ $nom }}</span>
                    @endforeach
                </div>

                <div class="tauler">
                    @foreach ($estats as $estat)
                        <section class="columna" data-estat="{{ $estat['clau'] }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h2 class="h6 text-white mb-0">{{ $estat['nom'] }}</h2>
                                <span class="comptador">0</span>
                            </div>

                            <div class="columna-tasques">
                                @foreach ($tasques as $tasca)
                                    @if ($tasca['estat'] == $estat['clau'])
                                        <article class="tasca tipus-{{ $tasca['tipus'] }} {{ $tasca['estat'] == 'feta' ? 'bloquejada' : '' }}"
                                            draggable="{{ $tasca['estat'] == 'feta' ? 'false' : 'true' }}"
                                            data-bs-toggle="modal" data-bs-target="#modalTasca"
                                            data-id="{{ $tasca['id'] }}"
                                            data-titol="{{ $tasca['titol'] }}"
                                            data-tipus="{{ $tipus[$tasca['tipus']] }}"
                                            data-clau-tipus="{{ $tasca['tipus'] }}"
                                            data-estat="{{ $tasca['estat'] }}"
                                            data-responsable="{{ $tasca['responsable'] }}"
                                            data-data="{{ $tasca['data'] }}"
                                            data-descripcio="{{ $tasca['descripcio'] }}">
                                            <span class="tipus tipus-{{ $tasca['tipus'] }} mb-2">{{ $tipus[$tasca['tipus']] }}</span>
                                            <p class="tasca-titol text-white">{{ $tasca['titol'] }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-secondary">{{ date('d/m', strtotime($tasca['data'])) }}</small>
                                                <span class="avatar" title="{{ $tasca['responsable'] }}">
                                                    {{ substr($tasca['responsable'], 0, 1) }}
                                                </span>
                                            </div>
                                        </article>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <p class="small text-secondary mt-3">
                    Arrossega les tasques entre columnes per canviar-ne l'estat. Les tasques de «Feta» ja no es poden moure.
                </p>
            </main>
        </div>
    </div>

    {{-- Modal amb el detall de la tasca --}}
    <div class="modal fade" id="modalTasca" tabindex="-1" aria-labelledby="detallTitol" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-secondary">
                    <div>
                        <span id="detallTipus" class="tipus mb-2"></span>
                        <h5 class="modal-title" id="detallTitol"></h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tancar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-12 col-md-8">
                            <h6 class="text-secondary text-uppercase small">Descripció</h6>
                            <p id="detallDescripcio"></p>

                            <h6 class="text-secondary text-uppercase small mt-4">Comentaris</h6>
                            <textarea class="form-control" rows="3" placeholder="Escriu un comentari..."></textarea>
                        </div>
                        <div class="col-12 col-md-4">
                            <h6 class="text-secondary text-uppercase small">Estat</h6>
                            <p id="detallEstat"></p>

                            <h6 class="text-secondary text-uppercase small">Responsable</h6>
                            <p id="detallResponsable"></p>

                            <h6 class="text-secondary text-uppercase small">Data de lliurament</h6>
                            <p id="detallData"></p>

                            <p id="detallBloqueig" class="small missatge-bloqueig d-none">
                                Aquesta tasca està feta i ja no es pot canviar d'estat.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Tancar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal per crear una tasca nova --}}
    <div class="modal fade" id="modalNovaTasca" tabindex="-1" aria-labelledby="novaTascaTitol" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-secondary text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="novaTascaTitol">Nova tasca</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tancar"></button>
                </div>

                <form id="formNovaTasca">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="novaTitol" class="form-label">Títol</label>
                            <input id="novaTitol" type="text" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="novaDescripcio" class="form-label">Descripció</label>
                            <textarea id="novaDescripcio" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label for="novaTipus" class="form-label">Tipus</label>
                                <select id="novaTipus" class="form-select">
                                    @foreach ($tipus as $clau => $nom)
                                        <option value="{{ $clau }}">{{ $nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="novaData" class="form-label">Data de lliurament</label>
                                <input id="novaData" type="date" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label for="novaResponsable" class="form-label">Responsable</label>
                                <select id="novaResponsable" class="form-select">
                                    <option>Alex Campano</option>
                                    <option>Marc García</option>
                                    <option>Roc Burnat</option>
                                    <option>Daniel Diez</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel·lar</button>
                        <button type="submit" class="btn btn-primary">Crear tasca</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/tauler.js') }}"></script>
@endsection
