@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        // Dades d'exemple. Quan el backend estigui fet, vindran del controlador.
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

        $tipus = [
            'disseny' => 'Disseny',
            'frontend' => 'Front-end',
            'backend' => 'Back-end',
            'bd' => 'Base de dades',
            'qa' => 'QA',
        ];

        // Tasques fetes cada dia de la setmana (exemple)
        $setmana = ['Dl' => 2, 'Dt' => 4, 'Dc' => 3, 'Dj' => 6, 'Dv' => 5];
        $maximSetmana = max($setmana);

        $total = count($tasques);
        $fetes = 0;
        $enProgres = 0;
        $perTipus = [];
        $perPersona = [];

        foreach ($tasques as $tasca) {
            if ($tasca['estat'] == 'feta') {
                $fetes++;
            }
            if ($tasca['estat'] == 'en-progres') {
                $enProgres++;
            }

            if (! isset($perTipus[$tasca['tipus']])) {
                $perTipus[$tasca['tipus']] = 0;
            }
            $perTipus[$tasca['tipus']]++;

            if (! isset($perPersona[$tasca['responsable']])) {
                $perPersona[$tasca['responsable']] = 0;
            }
            $perPersona[$tasca['responsable']]++;
        }

        $percentatgeFet = $total > 0 ? round($fetes * 100 / $total) : 0;
    @endphp

    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <div class="mb-3">
                    <h1 class="text-white mb-1">Analítiques</h1>
                    <p class="text-secondary mb-0">Rendiment i progrés del projecte</p>
                </div>

                @include('components.menu-projecte')

                <div class="row g-4 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="targeta-dada">
                            <p class="small text-secondary text-uppercase mb-1">Completat</p>
                            <p class="valor text-white mb-2">{{ $percentatgeFet }}%</p>
                            <div class="progress" role="progressbar" aria-label="Tasques completades"
                                aria-valuenow="{{ $percentatgeFet }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: {{ $percentatgeFet }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="targeta-dada">
                            <p class="small text-secondary text-uppercase mb-1">Tasques totals</p>
                            <p class="valor text-white mb-0">{{ $total }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="targeta-dada">
                            <p class="small text-secondary text-uppercase mb-1">En progrés ara</p>
                            <p class="valor text-white mb-0">{{ $enProgres }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="targeta-dada">
                            <p class="small text-secondary text-uppercase mb-1">Fetes</p>
                            <p class="valor text-white mb-0">{{ $fetes }}</p>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-xl-7">
                        <div class="targeta-dada">
                            <h2 class="h5 text-white mb-1">Evolució de tasques</h2>
                            <p class="small text-secondary mb-4">Tasques fetes cada dia d'aquesta setmana</p>

                            <div class="grafic-barres">
                                @foreach ($setmana as $dia => $quantitat)
                                    <div class="barra-dia">
                                        <small class="text-white mb-1">{{ $quantitat }}</small>
                                        <div class="barra" style="height: {{ round($quantitat * 100 / $maximSetmana) }}%"></div>
                                        <small>{{ $dia }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-5">
                        <div class="targeta-dada">
                            <h2 class="h5 text-white mb-1">Càrrega per tipus</h2>
                            <p class="small text-secondary mb-4">Percentatge de tasques de cada tipus</p>

                            @foreach ($perTipus as $clau => $quantitat)
                                @php
                                    $percentatge = round($quantitat * 100 / $total);
                                @endphp
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span class="text-white">{{ $tipus[$clau] }}</span>
                                        <span class="text-secondary">{{ $percentatge }}%</span>
                                    </div>
                                    <div class="progress" role="progressbar" aria-label="{{ $tipus[$clau] }}"
                                        aria-valuenow="{{ $percentatge }}" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar tipus-{{ $clau }}" style="width: {{ $percentatge }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="targeta-dada">
                            <h2 class="h5 text-white mb-3">Tasques per persona</h2>

                            <div class="table-responsive">
                                <table class="table table-dark table-borderless align-middle mb-0">
                                    <thead>
                                        <tr class="text-secondary small text-uppercase">
                                            <th>Membre</th>
                                            <th>Tasques</th>
                                            <th class="w-50">Part del total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perPersona as $persona => $quantitat)
                                            <tr>
                                                <td>
                                                    <span class="avatar me-2">{{ substr($persona, 0, 1) }}</span>
                                                    {{ $persona }}
                                                </td>
                                                <td>{{ $quantitat }}</td>
                                                <td>
                                                    <div class="progress" role="progressbar" aria-label="{{ $persona }}"
                                                        aria-valuenow="{{ round($quantitat * 100 / $total) }}" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="progress-bar" style="width: {{ round($quantitat * 100 / $total) }}%"></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection
