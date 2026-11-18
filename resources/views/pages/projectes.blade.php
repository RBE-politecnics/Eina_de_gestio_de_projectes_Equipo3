@extends('layouts.main')

@section('content')
    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">

                <div class="kt-capcalera">
                    <div>
                        <h1>Els meus projectes</h1>
                        <p class="text-secondary small mb-0">Tots els projectes en què participes.</p>
                    </div>

                    <form method="GET" action="{{ route('projectes') }}" class="d-flex gap-2">
                        <input type="search" name="q" value="{{ $cerca }}" class="form-control"
                               placeholder="Cerca un projecte...">
                        <button type="submit" class="btn btn-primary">Cerca</button>
                    </form>
                </div>

                <div class="row g-4">
                    @forelse ($projectes as $projecte)
                        @php
                            $tasquesProjecte = $tasques->where('projecte_id', $projecte->id);
                            $fetes = $tasquesProjecte->where('progres', 100)->count();
                            $progres = $tasquesProjecte->count() > 0 ? round($tasquesProjecte->avg('progres')) : 0;
                            $color = ($projecte->color && $projecte->color != '#FFFFFF') ? $projecte->color : '#007BFF';
                        @endphp

                        <div class="col-12 col-md-6 col-xl-4">
                            <a href="{{ route('tauler', $projecte->id) }}" class="kt-targeta kt-projecte" style="--projecte-color: {{ $color }};">
                                <span class="kt-icona mb-3">{{ strtoupper(substr($projecte->nom, 0, 2)) }}</span>
                                <h3 class="h5 fw-bold">{{ $projecte->nom }}</h3>
                                <p class="text-secondary small">{{ $projecte->descripcion ?: 'Sense descripció.' }}</p>

                                <p class="text-secondary small mt-auto mb-2">
                                    {{ $fetes }} / {{ $tasquesProjecte->count() }} tasques ·
                                    Creat el {{ $projecte->created_at->format('d/m/Y') }}
                                </p>

                                <div class="kt-progres mb-3" role="progressbar"
                                    aria-label="Progrés de {{ $projecte->nom }}"
                                    aria-valuenow="{{ $progres }}" aria-valuemin="0" aria-valuemax="100">
                                    <div style="width: {{ $progres }}%;"></div>
                                </div>

                                <span class="small fw-bold" style="color: {{ $color }};">Obrir projecte →</span>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="kt-targeta text-center text-secondary">
                                No s'ha trobat cap projecte.
                            </div>
                        </div>
                    @endforelse
                </div>
            </main>

        </div>
    </div>
@endsection
