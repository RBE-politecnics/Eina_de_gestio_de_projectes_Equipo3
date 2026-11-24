@extends('layouts.main')

@section('content')
    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">

                <div class="kt-capcalera">
                    <div>
                        <h1>Configuració</h1>
                        <p class="text-secondary small mb-0">Tipus de tasca i estats del flux de treball.</p>
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

                @if (! $potGestionar)
                    <p class="small text-secondary">Només els administradors dels projectes poden canviar això.</p>
                @endif

                <div class="row g-4">

                    {{-- Tipus de tasca --}}
                    <div class="col-12 col-xl-6">
                        <div class="kt-targeta">
                            <h2 class="h5 fw-bold mb-3">Tipus de tasca</h2>

                            @foreach ($tipus as $tipusTasca)
                                <form method="POST" action="{{ route('configuracio.tipus.actualitzar', $tipusTasca->id) }}"
                                      class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="color" name="color" value="{{ $tipusTasca->color }}"
                                           class="form-control form-control-color" aria-label="Color" @disabled(! $potGestionar)>
                                    <input type="text" name="nom" value="{{ $tipusTasca->nom }}"
                                           class="form-control w-auto flex-grow-1" aria-label="Nom" required @disabled(! $potGestionar)>
                                    @if ($potGestionar)
                                        <button type="submit" class="btn btn-primary btn-sm">Desar</button>
                                    @endif
                                </form>

                                @if ($potGestionar)
                                    <form method="POST" action="{{ route('configuracio.tipus.esborrar', $tipusTasca->id) }}"
                                          class="text-end mb-3"
                                          onsubmit="return confirm('Segur que vols eliminar aquest tipus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar {{ $tipusTasca->nom }}</button>
                                    </form>
                                @endif
                            @endforeach

                            @if ($potGestionar)
                                <form method="POST" action="{{ route('configuracio.tipus.guardar') }}"
                                      class="d-flex flex-wrap align-items-center gap-2 border-top pt-3">
                                    @csrf
                                    <input type="color" name="color" value="#007BFF" class="form-control form-control-color" aria-label="Color">
                                    <input type="text" name="nom" class="form-control w-auto flex-grow-1"
                                           placeholder="Nou tipus (per exemple, Documentació)" required>
                                    <button type="submit" class="btn btn-primary btn-sm">Afegir</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Estats --}}
                    <div class="col-12 col-xl-6">
                        <div class="kt-targeta">
                            <h2 class="h5 fw-bold mb-1">Estats</h2>
                            <p class="small text-secondary mb-3">
                                Cada estat és una columna del tauler. Un estat que bloqueja deixa les seves tasques sense poder-se canviar (per exemple, «Feta»).
                            </p>

                            @foreach ($estats as $estat)
                                <form method="POST" action="{{ route('configuracio.estats.actualitzar', $estat->id) }}"
                                      class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nom" value="{{ $estat->nom }}"
                                           class="form-control w-auto flex-grow-1" aria-label="Nom" required @disabled(! $potGestionar)>
                                    <div class="form-check mb-0">
                                        <input type="checkbox" name="bloqueja" value="1" id="bloqueja{{ $estat->id }}"
                                               class="form-check-input" @checked($estat->bloqueja) @disabled(! $potGestionar)>
                                        <label for="bloqueja{{ $estat->id }}" class="form-check-label small">Bloqueja</label>
                                    </div>
                                    @if ($potGestionar)
                                        <button type="submit" class="btn btn-primary btn-sm">Desar</button>
                                    @endif
                                </form>

                                @if ($potGestionar)
                                    <form method="POST" action="{{ route('configuracio.estats.esborrar', $estat->id) }}"
                                          class="text-end mb-3"
                                          onsubmit="return confirm('Segur que vols eliminar aquest estat?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar {{ $estat->nom }}</button>
                                    </form>
                                @endif
                            @endforeach

                            @if ($potGestionar)
                                <form method="POST" action="{{ route('configuracio.estats.guardar') }}"
                                      class="d-flex flex-wrap align-items-center gap-2 border-top pt-3">
                                    @csrf
                                    <input type="text" name="nom" class="form-control w-auto flex-grow-1"
                                           placeholder="Nou estat (per exemple, En revisió)" required>
                                    <div class="form-check mb-0">
                                        <input type="checkbox" name="bloqueja" value="1" id="bloquejaNou" class="form-check-input">
                                        <label for="bloquejaNou" class="form-check-label small">Bloqueja</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Afegir</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>
@endsection
