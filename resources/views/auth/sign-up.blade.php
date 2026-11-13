@extends('layouts.main')

@section('content')
<main class="bg-dark min-vh-100 d-flex align-items-center justify-content-center p-4">

    <div class="row w-100 rounded-4 overflow-hidden shadow-lg"
         style="max-width: 1024px; background: var(--kantum-background);">

        <!-- Presentación -->
        <div class="col-lg-6 bg-secondary text-white p-5 d-flex flex-column justify-content-center">

            <h2 class="fw-bold mb-5">KANTUM</h2>

            <h1 class="fw-bold mb-4">
                Organitza les idees<br>
                Avança en equip
            </h1>

            <p class="text-secondary">
                Un espai clar i compartit perquè el teu equip se centri en allò que realment importa.
            </p>
            
            <h4 class="fw-bold mt-auto">¿Ya tienes cuenta?</h4>

            <a href="{{ route('login') }}"
            class="btn btn-secondary btn-lg w-100 fw-semibold text-white mt-auto">
                Iniciar sesión
            </a>
        </div>

        <!-- Login -->
        <div class="col-lg-6 text-white p-5 d-flex flex-column justify-content-center"
             style="background: var(--kantum-form-panel);">

            <h2 class="fw-bold mb-3">Crear cuenta</h2>
            <p class="text-secondary mb-4">Introduce tus datos para continuar</p>

            <form method="POST" action="{{ route('sign.up.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label text-secondary">
                        Correo electrónico
                    </label>
                    <input type="email" id="email" name="email"
                           class="form-control form-control-lg"
                           placeholder="arnau@gmail.com"
                           value="{{ old('email') }}" required>

                    @error('email')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-secondary">
                        Contraseña
                    </label>

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
                    <label for="password_confirmation" class="form-label text-secondary">
                        Confirmar contraseña
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control form-control-lg"
                           placeholder="••••••••" required>

                    @error('password')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold text-white">
                    Crear cuenta
                </button>
            </form>

        </div>
    </div>
</main>
@endsection