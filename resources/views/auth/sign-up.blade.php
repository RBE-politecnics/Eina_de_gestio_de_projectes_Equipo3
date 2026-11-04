@extends('layouts.main')

@section('content')
<main class="bg-dark min-vh-100 d-flex align-items-center justify-content-center p-4">

    <div class="row w-100 rounded-4 overflow-hidden shadow-lg"
         style="max-width: 1024px; background: var(--kantum-background);">

        <!-- Presentación -->
        <div class="col-lg-6 bg-secondary text-white p-5 d-flex flex-column justify-content-center">

            <h2 class="fw-bold mb-5">KANTUM</h2>

            <h1 class="fw-bold mb-4">
                Organitza les idees.<br>
                Avança en equip.
            </h1>

            <p class="text-secondary">
                Un espai clar i compartit perquè el teu equip se centri en allò que realment importa.
            </p>
            
            <h4 class="fw-bold mt-auto">¿No tienes cuenta?</h4>

            <button type="submit" class="btn btn-secondary btn-lg w-100 fw-semibold text-white mt-auto">
                Crear cuenta →
            </button>
        </div>

        <!-- Login -->
        <div class="col-lg-6 text-white p-5"
             style="background: var(--kantum-form-panel);">

            <p class="mb-3" style="color: #93c5fd;">Benvingut de nou</p>

            <h2 class="fw-bold mb-3">Torna al teu espai</h2>
            <p class="text-secondary mb-4">Introdueix les teves dades per continuar.</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label text-secondary">
                        Correu electrònic
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
                        Contrasenya
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

                <div class="form-check mb-4">
                    <input type="checkbox" id="remember" name="remember"
                           class="form-check-input" @checked(old('remember'))>
                    <label for="remember" class="form-check-label text-secondary">
                        Recorda'm
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold text-white">
                    Iniciar sesión →
                </button>
            </form>

        </div>
    </div>
</main>
@endsection