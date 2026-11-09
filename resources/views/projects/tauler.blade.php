@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        // Dades d'exemple fins que el backend estigui fet
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

        $tasques = [
            ['titol' => 'Definir estructura de la web', 'tipus' => 'disseny', 'estat' => 'feta', 'responsable' => 'Alex Campano', 'data' => '03/11/2026', 'descripcio' => 'Decidir quines pantalles té l\'aplicació.'],
            ['titol' => 'Dissenyar mockups', 'tipus' => 'disseny', 'estat' => 'feta', 'responsable' => 'Marc García', 'data' => '04/11/2026', 'descripcio' => 'Passar el wireframe als colors del manual.'],
            ['titol' => 'Vista del tauler Kanban', 'tipus' => 'frontend', 'estat' => 'en-progres', 'responsable' => 'Alex Campano', 'data' => '12/11/2026', 'descripcio' => 'Columnes per estat i tasques que es poden arrossegar.'],
            ['titol' => 'CRUD de tasques', 'tipus' => 'backend', 'estat' => 'en-progres', 'responsable' => 'Roc Burnat', 'data' => '14/11/2026', 'descripcio' => 'Crear, modificar i eliminar tasques des del servidor.'],
            ['titol' => 'Taula de tipus de tasca', 'tipus' => 'bd', 'estat' => 'per-fer', 'responsable' => 'Roc Burnat', 'data' => '15/11/2026', 'descripcio' => 'Afegir la taula de tipus amb el nom i el color.'],
            ['titol' => 'Vista del calendari', 'tipus' => 'frontend', 'estat' => 'per-fer', 'responsable' => 'Alex Campano', 'data' => '18/11/2026', 'descripcio' => 'Mostrar les dates de lliurament en un mes.'],
            ['titol' => 'Provar el login', 'tipus' => 'qa', 'estat' => 'per-fer', 'responsable' => 'Marc García', 'data' => '20/11/2026', 'descripcio' => 'Comprovar que els errors es mostren bé.'],
        ];
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

                <form id="formNovaTasca">
                    <div class="modal-body">
                        <label for="novaTitol" class="form-label">Títol</label>
                        <input id="novaTitol" type="text" class="form-control mb-3" required>

                        <label for="novaDescripcio" class="form-label">Descripció</label>
                        <textarea id="novaDescripcio" class="form-control mb-3" rows="2"></textarea>

                        <label for="novaTipus" class="form-label">Tipus</label>
                        <select id="novaTipus" class="form-select mb-3">
                            @foreach ($tipus as $clau => $nom)
                                <option value="{{ $clau }}">{{ $nom }}</option>
                            @endforeach
                        </select>

                        <label for="novaResponsable" class="form-label">Responsable</label>
                        <select id="novaResponsable" class="form-select mb-3">
                            <option>Alex Campano</option>
                            <option>Marc García</option>
                            <option>Roc Burnat</option>
                            <option>Daniel Diez</option>
                        </select>

                        <label for="novaData" class="form-label">Data de lliurament</label>
                        <input id="novaData" type="date" class="form-control" required>
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
