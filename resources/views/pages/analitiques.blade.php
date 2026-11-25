@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        // Tasques dels projectes de l'usuari
        $idsProjectes = \App\Models\Projecte_Usuari::where('users_id', auth()->id())->pluck('projecte_id');
        $tasquesBD = \App\Models\Tasques::with(['estat', 'usuari', 'tipus'])->whereIn('projecte_id', $idsProjectes)->get();
        $estatsBD = \App\Models\Estats::orderBy('id')->get();
        $primerEstat = $estatsBD->first()?->id;

        // Comptadors: comencen a 0 i sumem 1 per cada tasca
        $total = $tasquesBD->count();
        $fetes = 0;
        $enProgres = 0;
        $perEstat = [];
        $perTipus = [];
        $perPersona = [];

        foreach ($estatsBD as $estatBD) {
            $perEstat[$estatBD->nom] = 0;
        }

        foreach ($tasquesBD as $tasca) {
            if ($tasca->estat?->bloqueja) {
                $fetes++;
            } elseif ($tasca->estats_id != $primerEstat) {
                $enProgres++;
            }

            $nomEstat = $tasca->estat?->nom;
            $perEstat[$nomEstat] = ($perEstat[$nomEstat] ?? 0) + 1;

            $nomTipus = $tasca->tipus?->nom ?? 'Sense tipus';
            $perTipus[$nomTipus] = ($perTipus[$nomTipus] ?? 0) + 1;

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

                            @foreach ($perEstat as $nomEstat => $quantitat)
                                <div class="d-flex justify-content-between small text-white">
                                    <span>{{ $nomEstat }}</span>
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
                            <h2 class="h5 text-white mb-4">Tasques per tipus</h2>

                            @foreach ($perTipus as $nomTipus => $quantitat)
                                <div class="d-flex justify-content-between small text-white">
                                    <span>{{ $nomTipus }}</span>
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
