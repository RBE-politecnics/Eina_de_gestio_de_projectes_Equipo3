@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        $estats = [
            'per-fer' => 'Per fer',
            'en-progres' => 'En progrés',
            'feta' => 'Feta',
        ];

        // Id de l'estat de cada columna (el busquem pel nom que té a la BD)
        $idColumna = [];
        foreach ($estatsDisponibles as $estatBD) {
            $clau = match ($estatBD->nom) {
                'Pendent', 'Per fer' => 'per-fer',
                'En curs', 'En progrés' => 'en-progres',
                'Completada', 'Feta' => 'feta',
                default => null,
            };

            if ($clau) {
                $idColumna[$clau] = $estatBD->id;
            }
        }

        $tipus = [
            'disseny' => 'Disseny',
            'frontend' => 'Front-end',
            'backend' => 'Back-end',
            'bd' => 'Base de dades',
            'qa' => 'QA',
        ];

        $tasques = $tasquesProjecte
            ->map(function (\App\Models\Tasques $tasca) use ($esAdmin): array {
                $estat = strtolower($tasca->estat?->nom ?? '');

                return [
                    'id' => $tasca->id,
                    'estatId' => $tasca->estats_id,
                    'dataIso' => $tasca->data_finalitzacio?->format('Y-m-d') ?? '',
                    'pot' => $esAdmin || $tasca->users_id == auth()->id(),
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
                @if (! $projecte)
                    <h1 class="mb-4">Tauler</h1>
                    <div class="kt-targeta text-center text-secondary">
                        Encara no participes en cap projecte. Crea'n un des del Dashboard.
                    </div>
                @else
                <div class="kt-capcalera">
                    <div>
                        <h1>{{ $projecte->nom }}</h1>
                        <p class="text-secondary small mb-0">{{ $tasques->count() }} tasques</p>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <select class="form-select w-auto" aria-label="Canvia de projecte"
                                onchange="window.location.href = this.value">
                            @foreach ($projectes as $opcio)
                                <option value="{{ route('tauler', $opcio->id) }}" @selected($opcio->id == $projecte->id)>{{ $opcio->nom }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovaTasca">
                            + Nova tasca
                        </button>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="tauler">
                    @foreach ($estats as $clauEstat => $nomEstat)
                        <section class="columna" data-estat="{{ $clauEstat }}" data-nom="{{ $nomEstat }}"
                            data-estat-id="{{ $idColumna[$clauEstat] ?? '' }}">
                            <h2 class="h6 text-white">
                                {{ $nomEstat }} <span class="comptador">0</span>
                            </h2>

                            <div class="columna-tasques">
                                @foreach ($tasques as $tasca)
                                    @if ($tasca['estat'] == $clauEstat)
                                        <article class="tasca vora-{{ $tasca['tipus'] }} {{ $clauEstat == 'feta' ? 'bloquejada' : '' }}"
                                            draggable="{{ $clauEstat != 'feta' && $tasca['pot'] ? 'true' : 'false' }}"
                                            data-bs-toggle="modal" data-bs-target="#modalTasca"
                                            data-id="{{ $tasca['id'] }}"
                                            data-estat-id="{{ $tasca['estatId'] }}"
                                            data-data-iso="{{ $tasca['dataIso'] }}"
                                            data-pot="{{ $tasca['pot'] ? '1' : '0' }}"
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
                    Arrossega les teves tasques per canviar-ne l'estat, o fes clic en una per editar-la. Les tasques de «Feta» ja no es poden canviar.
                </p>
                @endif
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
                        Aquesta tasca està feta i ja no es pot canviar.
                    </p>
                    <p id="detallSensePermis" class="small text-secondary mt-3 d-none">
                        Només el responsable de la tasca o l'administrador la poden editar.
                    </p>

                    <div id="zonaEditar" class="d-none border-top pt-3 mt-3">
                        <h6 class="mb-3">Editar tasca</h6>

                        <form id="formEditarTasca" method="POST" action="" data-base="{{ url('/tasques') }}">
                            @csrf
                            @method('PUT')

                            <label for="editTitol" class="form-label">Títol</label>
                            <input id="editTitol" name="nom" type="text" class="form-control mb-3" required>

                            <label for="editDescripcio" class="form-label">Descripció</label>
                            <textarea id="editDescripcio" name="comentaris" class="form-control mb-3" rows="2"></textarea>

                            <label for="editEstat" class="form-label">Estat</label>
                            <select id="editEstat" name="estats_id" class="form-select mb-3" required>
                                @foreach ($estatsDisponibles as $estat)
                                    <option value="{{ $estat->id }}">{{ $estat->nom }}</option>
                                @endforeach
                            </select>

                            <label for="editData" class="form-label">Data de lliurament</label>
                            <input id="editData" name="data_finalitzacio" type="date" class="form-control mb-3">

                            <button type="submit" class="btn btn-primary">Desar canvis</button>
                        </form>

                        <form id="formEliminarTasca" method="POST" action="" class="mt-2"
                              onsubmit="return confirm('Segur que vols eliminar aquesta tasca?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Eliminar tasca</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulari amagat: el fa servir el JS quan s'arrossega una tasca --}}
    <form id="formMoure" method="POST" action="" data-base="{{ url('/tasques') }}" class="d-none">
        @csrf
        @method('PUT')
        <input type="hidden" id="moureEstat" name="estats_id">
    </form>

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
                        <input type="hidden" name="projecte_id" value="{{ $projecte?->id }}">

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
