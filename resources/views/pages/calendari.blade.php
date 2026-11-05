@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        // Dades d'exemple. Quan el backend estigui fet, vindran del controlador.
        $tasques = [
            ['titol' => 'Definir estructura de la web', 'tipus' => 'disseny', 'data' => '2026-11-03'],
            ['titol' => 'Dissenyar mockups', 'tipus' => 'disseny', 'data' => '2026-11-04'],
            ['titol' => 'Primer desplegament', 'tipus' => 'backend', 'data' => '2026-11-10'],
            ['titol' => 'Vista del tauler Kanban', 'tipus' => 'frontend', 'data' => '2026-11-12'],
            ['titol' => 'CRUD de tasques', 'tipus' => 'backend', 'data' => '2026-11-14'],
            ['titol' => 'Taules de tipus de tasca', 'tipus' => 'bd', 'data' => '2026-11-15'],
            ['titol' => 'Vista del calendari', 'tipus' => 'frontend', 'data' => '2026-11-18'],
            ['titol' => 'Provar el login i el registre', 'tipus' => 'qa', 'data' => '2026-11-20'],
        ];

        $mesos = ['', 'Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'];
        $diesSetmana = ['Dl', 'Dt', 'Dc', 'Dj', 'Dv', 'Ds', 'Dg'];

        // Mes que es mostra (?mes=11&any=2026). Per defecte, el mes actual.
        $mes = request('mes', date('n'));
        $any = request('any', date('Y'));

        $primerDia = mktime(0, 0, 0, $mes, 1, $any);
        $diesDelMes = date('t', $primerDia);
        $buitsInici = date('N', $primerDia) - 1;

        $mesAnterior = $mes - 1;
        $anyAnterior = $any;
        if ($mesAnterior < 1) {
            $mesAnterior = 12;
            $anyAnterior = $any - 1;
        }

        $mesSeguent = $mes + 1;
        $anySeguent = $any;
        if ($mesSeguent > 12) {
            $mesSeguent = 1;
            $anySeguent = $any + 1;
        }
    @endphp

    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                    <div>
                        <h1 class="text-white mb-1">Calendari</h1>
                        <p class="text-secondary mb-0">Dates de lliurament de les tasques</p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('calendari', ['mes' => $mesAnterior, 'any' => $anyAnterior]) }}" class="btn btn-outline-light btn-sm">←</a>
                        <span class="text-white fw-semibold px-2">{{ $mesos[$mes] }} {{ $any }}</span>
                        <a href="{{ route('calendari', ['mes' => $mesSeguent, 'any' => $anySeguent]) }}" class="btn btn-outline-light btn-sm">→</a>
                        <a href="{{ route('calendari') }}" class="btn btn-primary btn-sm">Avui</a>
                    </div>
                </div>

                @include('components.menu-projecte')

                <div class="calendari mb-2">
                    @foreach ($diesSetmana as $nomDia)
                        <div class="dia-setmana">{{ $nomDia }}</div>
                    @endforeach
                </div>

                <div class="calendari">
                    @for ($i = 0; $i < $buitsInici; $i++)
                        <div class="dia buit"></div>
                    @endfor

                    @for ($dia = 1; $dia <= $diesDelMes; $dia++)
                        @php
                            $dataDia = date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $any));
                        @endphp

                        <div class="dia {{ $dataDia == date('Y-m-d') ? 'avui' : '' }}">
                            <span class="dia-numero">{{ $dia }}</span>

                            @foreach ($tasques as $tasca)
                                @if ($tasca['data'] == $dataDia)
                                    <span class="esdeveniment tipus-{{ $tasca['tipus'] }}" title="{{ $tasca['titol'] }}">
                                        {{ $tasca['titol'] }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endfor
                </div>
            </main>
        </div>
    </div>
@endsection
