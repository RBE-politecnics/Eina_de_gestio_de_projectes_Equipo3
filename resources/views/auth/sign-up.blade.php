@extends('layouts.main')

@section('content')
<main class="kt-entrada">
    <div class="kt-entrada-caixa text-white">

        <div class="kt-entrada-esquerra">
            <div class="kt-marca">
                <img src="{{ Vite::asset('resources/assets/logo-kantum.png') }}" alt="">
                <span>KANTUM</span>
            </div>

            <span class="kt-etiqueta-rodona align-self-start">Projectes sense estrès</span>

            <h1>Comença avui.<br>Avança en equip.</h1>

            <p class="text-secondary">
                Crea el teu compte i porta les idees del teu equip a un lloc on tothom les veu.
            </p>

            <ul class="kt-llista">
                <li>Tasques clares, prioritats visibles</li>
                <li>Col·laboració sense soroll</li>
                <li>Progrés que motiva</li>
            </ul>

            <p class="kt-peu mb-0">Creat per a estudiants i equips que volen arribar més lluny.</p>
        </div>

        <div class="kt-entrada-dreta">
            <p class="kt-data text-primary">Nou a Kantum</p>
            <h2 class="fw-bold">Crea el teu compte</h2>
            <p class="text-secondary mb-4">Omple les dades per començar.</p>

            <form method="POST" action="{{ route('sign.up.store') }}">
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

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label text-secondary small">
                        Confirma la contrasenya
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control form-control-lg"
                           placeholder="••••••••" required>

                    @error('password')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">Crear compte</button>
            </form>

            <p class="text-center text-secondary small mt-4 mb-0">
                Ja tens compte?
                <a href="{{ route('login') }}" class="text-white fw-bold text-decoration-none">Inicia la sessió</a>
            </p>
        </div>
    </div>
</main>
@endsection
