@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        // Tasques dels projectes de l'usuari
        $idsProjectes = \App\Models\Projecte_Usuari::where('users_id', auth()->id())->pluck('projecte_id');
        $tasquesBD = \App\Models\Tasques::with(['estat', 'usuari'])->whereIn('projecte_id', $idsProjectes)->get();

        $estats = ['per-fer' => 'Per fer', 'en-progres' => 'En progrés', 'feta' => 'Feta'];

        // Comptadors: comencen a 0 i sumem 1 per cada tasca
        $total = $tasquesBD->count();
        $fetes = 0;
        $enProgres = 0;
        $perEstat = ['per-fer' => 0, 'en-progres' => 0, 'feta' => 0];
        $perPersona = [];

        foreach ($tasquesBD as $tasca) {
            $nomEstat = strtolower($tasca->estat?->nom ?? '');

            if ($nomEstat == 'completada') {
                $clauEstat = 'feta';
                $fetes++;
            } elseif ($nomEstat == 'en curs') {
                $clauEstat = 'en-progres';
                $enProgres++;
            } else {
                $clauEstat = 'per-fer';
            }

            $perEstat[$clauEstat]++;

            $persona = $tasca->usuari?->email ?? 'Sense assignar';
            $perPersona[$persona] = ($perPersona[$persona] ?? 0) + 1;
        }

        // Evitem dividir per zero si encara no hi ha tasques
        $divisor = $total > 0 ? $total : 1;
        $percentatgeFet = round($fetes * 100 / $divisor);
    @endphp

    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <h1 class="mb-4">Analítiques</h1>

                <div class="row g-4 mb-4">
                    <div class="col-6 col-xl-3">
                        <div class="targeta-dada">
                            <p class="small text-secondary mb-1">COMPLETAT</p>
                            <p class="valor">{{ $percentatgeFet }}%</p>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="targeta-dada">
                            <p class="small text-secondary mb-1">TASQUES TOTALS</p>
                            <p class="valor">{{ $total }}</p>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="targeta-dada">
                            <p class="small text-secondary mb-1">EN PROGRÉS</p>
                            <p class="valor">{{ $enProgres }}</p>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="targeta-dada">
                            <p class="small text-secondary mb-1">FETES</p>
                            <p class="valor">{{ $fetes }}</p>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-xl-6">
                        <div class="targeta-dada">
                            <h2 class="h5 text-white mb-4">Tasques per estat</h2>

                            @foreach ($perEstat as $clau => $quantitat)
                                <div class="d-flex justify-content-between small text-white">
                                    <span>{{ $estats[$clau] }}</span>
                                    <span>{{ $quantitat }}</span>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar" style="width: {{ $quantitat * 100 / $divisor }}%"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 col-xl-6">
                        <div class="targeta-dada">
                            <h2 class="h5 text-white mb-4">Tasques per persona</h2>

                            @foreach ($perPersona as $persona => $quantitat)
                                <div class="d-flex justify-content-between small text-white">
                                    <span>{{ $persona }}</span>
                                    <span>{{ $quantitat }}</span>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar" style="width: {{ $quantitat * 100 / $divisor }}%"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
