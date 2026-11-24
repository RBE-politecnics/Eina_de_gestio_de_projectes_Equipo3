@extends('layouts.main')

@section('content')
<main class="kt-entrada">
    <div class="kt-entrada-caixa text-white">

        <div class="kt-entrada-esquerra">
            <div class="kt-marca">
                <img src="{{ Vite::asset('resources/assets/logo-kantum.svg') }}" alt="">
                <span>KANTUM</span>
            </div>

            <span class="kt-etiqueta-rodona align-self-start">Projectes sense estrès</span>

            <h1>Organitza les idees.<br>Avança en equip.</h1>

            <p class="text-secondary">
                Un espai clar i compartit perquè el teu equip se centri en allò que realment importa.
            </p>

            <ul class="kt-llista">
                <li>Tasques clares, prioritats visibles</li>
                <li>Col·laboració sense soroll</li>
                <li>Progrés que motiva</li>
            </ul>

            <p class="kt-peu mb-0">Creat per a estudiants i equips que volen arribar més lluny.</p>
        </div>

        <div class="kt-entrada-dreta">
            <p class="kt-data text-primary">Benvingut de nou</p>
            <h2 class="fw-bold">Torna al teu espai</h2>
            <p class="text-secondary mb-4">Introdueix les teves dades per continuar.</p>

            <form method="POST" action="{{ route('login.store') }}" id="formAuth"
                  data-api="{{ url('/api/login') }}" data-desti="{{ route('dashboard') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label text-secondary small">Correu electrònic</label>
                    <input type="email" id="email" name="email"
                           class="form-control form-control-lg"
                           placeholder="nom@correu.cat"
                           value="{{ old('email') }}" required>

                    @error('email')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-secondary small">Contrasenya</label>

                    <div class="input-group">
                        <input type="password" id="password" name="password"
                            class="form-control form-control-lg"
                            placeholder="••••••••" required>

                        <button type="button" class="btn btn-outline-secondary"
                                onclick="document.getElementById('password').type =
                                document.getElementById('password').type === 'password' ? 'text' : 'password'">
                            Mostrar
                        </button>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" id="remember" name="remember"
                           class="form-check-input" @checked(old('remember'))>
                    <label for="remember" class="form-check-label text-secondary small">Recorda'm</label>
                </div>

                <p id="errorAuth" class="text-danger small d-none"></p>

                <button type="submit" class="btn btn-primary btn-lg w-100">Entrar a Kantum</button>
            </form>

            <p class="text-center text-secondary small mt-4 mb-0">
                Encara no tens compte?
                <a href="{{ route('sign.up') }}" class="text-white fw-bold text-decoration-none">Crea'n un de nou</a>
            </p>
        </div>
    </div>
</main>
<script src="{{ asset('js/auth.js') }}"></script>
@endsection
