@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

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
                            <p class="text-secondary small mb-0"><span id="totalTasques">0</span> tasques · {{ $membres->count() }} membres</p>
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
                            @if ($esAdmin)
                                <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalProjecte">
                                    Editar projecte
                                </button>
                            @endif
                        </div>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif

                    <div id="missatgeTauler" class="alert d-none" role="alert"></div>

                    <div class="tauler" id="tauler" style="--columnes: {{ $estatsDisponibles->count() }};"
                         data-projecte="{{ $projecte->id }}" data-usuari="{{ auth()->id() }}"
                         data-admin="{{ $esAdmin ? '1' : '0' }}" data-api="{{ url('/api') }}">
                        @foreach ($estatsDisponibles as $estat)
                            <section class="columna" data-estat-id="{{ $estat->id }}" data-nom="{{ $estat->nom }}"
                                     data-bloqueja="{{ $estat->bloqueja ? '1' : '0' }}">
                                <h2 class="h6 text-white">
                                    {{ $estat->nom }} <span class="comptador">0</span>
                                </h2>

                                <div class="columna-tasques"></div>
                            </section>
                        @endforeach
                    </div>

                    <p class="small text-secondary mt-3">
                        Arrossega les teves tasques per canviar-ne l'estat, o fes clic en una per veure-la i editar-la. Les tasques en un estat que bloqueja (com «Feta») ja no es poden canviar.
                    </p>
                @endif
            </main>
        </div>
    </div>

    @if ($projecte)
        {{-- Detall i edició de la tasca --}}
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

                        <div id="zonaAssignar" class="d-none mt-3">
                            <p class="small text-secondary">Només el responsable o l'administrador poden editar aquesta tasca.</p>
                            <button type="button" id="botoAssignar" class="btn btn-outline-light btn-sm">Assigna-me aquesta tasca</button>
                        </div>

                        <div id="zonaEditar" class="d-none border-top pt-3 mt-3">
                            <h6 class="mb-3">Editar tasca</h6>

                            <form id="formEditarTasca">
                                <label for="editTitol" class="form-label">Títol</label>
                                <input id="editTitol" type="text" class="form-control mb-3" required>

                                <label for="editDescripcio" class="form-label">Descripció</label>
                                <textarea id="editDescripcio" class="form-control mb-3" rows="2"></textarea>

                                <label for="editTipus" class="form-label">Tipus</label>
                                <select id="editTipus" class="form-select mb-3">
                                    <option value="">Sense tipus</option>
                                    @foreach ($tipusDisponibles as $tipusTasca)
                                        <option value="{{ $tipusTasca->id }}">{{ $tipusTasca->nom }}</option>
                                    @endforeach
                                </select>

                                <label for="editEstat" class="form-label">Estat</label>
                                <select id="editEstat" class="form-select mb-3" required>
                                    @foreach ($estatsDisponibles as $estat)
                                        <option value="{{ $estat->id }}">{{ $estat->nom }}</option>
                                    @endforeach
                                </select>

                                @if ($esAdmin)
                                    <label for="editResponsable" class="form-label">Responsable</label>
                                    <select id="editResponsable" class="form-select mb-3">
                                        @foreach ($membres as $membre)
                                            <option value="{{ $membre->id }}">{{ $membre->email }}</option>
                                        @endforeach
                                    </select>
                                @endif

                                <label for="editData" class="form-label">Data de lliurament</label>
                                <input id="editData" type="date" class="form-control mb-3">

                                <button type="submit" class="btn btn-primary">Desar canvis</button>
                                <button type="button" id="botoEliminar" class="btn btn-outline-danger">Eliminar tasca</button>
                            </form>
                        </div>
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
                                <option value="">Sense tipus</option>
                                @foreach ($tipusDisponibles as $tipusTasca)
                                    <option value="{{ $tipusTasca->id }}">{{ $tipusTasca->nom }}</option>
                                @endforeach
                            </select>

                            <label for="novaEstat" class="form-label">Estat</label>
                            <select id="novaEstat" class="form-select mb-3" required>
                                @foreach ($estatsDisponibles as $estat)
                                    <option value="{{ $estat->id }}">{{ $estat->nom }}</option>
                                @endforeach
                            </select>

                            @if ($esAdmin)
                                <label for="novaResponsable" class="form-label">Responsable</label>
                                <select id="novaResponsable" class="form-select mb-3">
                                    @foreach ($membres as $membre)
                                        <option value="{{ $membre->id }}" @selected($membre->id == auth()->id())>{{ $membre->email }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <label for="novaData" class="form-label">Data de lliurament</label>
                            <input id="novaData" type="date" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel·lar</button>
                            <button type="submit" class="btn btn-primary">Crear tasca</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Editar i eliminar el projecte (només l'administrador) --}}
        @if ($esAdmin)
            <div class="modal fade" id="modalProjecte" tabindex="-1" aria-labelledby="projecteTitol" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content bg-secondary text-white">
                        <div class="modal-header">
                            <h5 class="modal-title" id="projecteTitol">Editar projecte</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tancar"></button>
                        </div>

                        <form method="POST" action="{{ route('projects.update', $projecte->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <label for="projecteNom" class="form-label">Nom</label>
                                <input id="projecteNom" name="nom" type="text" class="form-control mb-3"
                                       value="{{ $projecte->nom }}" required>

                                <label for="projecteDescripcio" class="form-label">Descripció</label>
                                <textarea id="projecteDescripcio" name="descripcion" class="form-control" rows="3">{{ $projecte->descripcion }}</textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Desar canvis</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('projects.destroy', $projecte->id) }}"
                              class="px-3 pb-3"
                              onsubmit="return confirm('Segur que vols eliminar el projecte i totes les seves tasques?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">Eliminar projecte</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <script src="{{ asset('js/tauler.js') }}"></script>
    @endif
@endsection
