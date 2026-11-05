@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        // Dades d'exemple fins que el backend estigui fet
        $tasques = [
            ['tipus' => 'disseny', 'estat' => 'feta', 'responsable' => 'Alex Campano'],
            ['tipus' => 'disseny', 'estat' => 'feta', 'responsable' => 'Marc García'],
            ['tipus' => 'frontend', 'estat' => 'en-progres', 'responsable' => 'Alex Campano'],
            ['tipus' => 'backend', 'estat' => 'en-progres', 'responsable' => 'Roc Burnat'],
            ['tipus' => 'bd', 'estat' => 'per-fer', 'responsable' => 'Roc Burnat'],
            ['tipus' => 'frontend', 'estat' => 'per-fer', 'responsable' => 'Alex Campano'],
            ['tipus' => 'qa', 'estat' => 'per-fer', 'responsable' => 'Marc García'],
            ['tipus' => 'frontend', 'estat' => 'feta', 'responsable' => 'Marc García'],
        ];

        $tipus = ['disseny' => 'Disseny', 'frontend' => 'Front-end', 'backend' => 'Back-end', 'bd' => 'Base de dades', 'qa' => 'QA'];

        // Comptadors: comencen a 0 i sumem 1 per cada tasca
        $total = count($tasques);
        $fetes = 0;
        $enProgres = 0;
        $perTipus = ['disseny' => 0, 'frontend' => 0, 'backend' => 0, 'bd' => 0, 'qa' => 0];
        $perPersona = ['Alex Campano' => 0, 'Marc García' => 0, 'Roc Burnat' => 0, 'Daniel Diez' => 0];

        foreach ($tasques as $tasca) {
            if ($tasca['estat'] == 'feta') {
                $fetes++;
            }
            if ($tasca['estat'] == 'en-progres') {
                $enProgres++;
            }
            $perTipus[$tasca['tipus']]++;
            $perPersona[$tasca['responsable']]++;
        }

        $percentatgeFet = round($fetes * 100 / $total);
    @endphp

    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <h1 class="text-white mb-4">Analítiques</h1>

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
                            <h2 class="h5 text-white mb-4">Tasques per tipus</h2>

                            @foreach ($perTipus as $clau => $quantitat)
                                <div class="d-flex justify-content-between small text-white">
                                    <span>{{ $tipus[$clau] }}</span>
                                    <span>{{ $quantitat }}</span>
                                </div>
                                <div class="progress mb-3">
                                    <div class="progress-bar tipus-{{ $clau }}" style="width: {{ $quantitat * 100 / $total }}%"></div>
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
                                    <div class="progress-bar" style="width: {{ $quantitat * 100 / $total }}%"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
