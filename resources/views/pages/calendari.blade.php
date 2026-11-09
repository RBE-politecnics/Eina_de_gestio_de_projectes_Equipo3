@extends('layouts.main')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/vistes.css') }}">

    @php
        // Dades d'exemple fins que el backend estigui fet
        $tasques = [
            ['titol' => 'Definir estructura de la web', 'tipus' => 'disseny', 'data' => '2026-11-03'],
            ['titol' => 'Dissenyar mockups', 'tipus' => 'disseny', 'data' => '2026-11-04'],
            ['titol' => 'Primer desplegament', 'tipus' => 'backend', 'data' => '2026-11-10'],
            ['titol' => 'Vista del tauler Kanban', 'tipus' => 'frontend', 'data' => '2026-11-12'],
            ['titol' => 'CRUD de tasques', 'tipus' => 'backend', 'data' => '2026-11-14'],
            ['titol' => 'Taula de tipus de tasca', 'tipus' => 'bd', 'data' => '2026-11-15'],
            ['titol' => 'Vista del calendari', 'tipus' => 'frontend', 'data' => '2026-11-18'],
            ['titol' => 'Provar el login', 'tipus' => 'qa', 'data' => '2026-11-20'],
        ];

        $mesos = ['', 'Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'];

        // Mes que es mostra. Si a la URL no hi ha ?mes= ni ?any=, el mes actual.
        $mes = request('mes', date('n'));
        $any = request('any', date('Y'));

        $primerDia = mktime(0, 0, 0, $mes, 1, $any);
        $diesDelMes = date('t', $primerDia);
        $buitsInici = date('N', $primerDia) - 1; // 1 = dilluns, 7 = diumenge

        // mktime ja canvia d'any sol si el mes és 0 o 13
        $anterior = mktime(0, 0, 0, $mes - 1, 1, $any);
        $seguent = mktime(0, 0, 0, $mes + 1, 1, $any);
    @endphp

    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <h1 class="text-white">Calendari</h1>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('calendari', ['mes' => date('n', $anterior), 'any' => date('Y', $anterior)]) }}" class="btn btn-outline-light btn-sm">←</a>
                        <span class="text-white fw-semibold px-2">{{ $mesos[date('n', $primerDia)] }} {{ $any }}</span>
                        <a href="{{ route('calendari', ['mes' => date('n', $seguent), 'any' => date('Y', $seguent)]) }}" class="btn btn-outline-light btn-sm">→</a>
                    </div>
                </div>

                <div class="calendari">
                    @foreach (['Dl', 'Dt', 'Dc', 'Dj', 'Dv', 'Ds', 'Dg'] as $nomDia)
                        <div class="dia-setmana">{{ $nomDia }}</div>
                    @endforeach

                    @for ($i = 0; $i < $buitsInici; $i++)
                        <div></div>
                    @endfor

                    @for ($dia = 1; $dia <= $diesDelMes; $dia++)
                        @php
                            $dataDia = date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $any));
                        @endphp

                        <div class="dia {{ $dataDia == date('Y-m-d') ? 'avui' : '' }}">
                            <span class="dia-numero">{{ $dia }}</span>

                            @foreach ($tasques as $tasca)
                                @if ($tasca['data'] == $dataDia)
                                    <span class="esdeveniment tipus-{{ $tasca['tipus'] }}" title="{{ $tasca['titol'] }}">{{ $tasca['titol'] }}</span>
                                @endif
                            @endforeach
                        </div>
                    @endfor
                </div>
            </main>
        </div>
    </div>
@endsection
