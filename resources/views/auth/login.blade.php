@extends('layouts.main')

@section('content')
    <main class="login-page bg-dark d-flex align-items-center justify-content-center px-3 py-4">
        <section class="login-card row g-0 w-100 rounded shadow-lg">
            <div class="col-lg-6 bg-secondary text-light d-flex flex-column justify-content-between gap-5 p-4 p-sm-5">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-5">
                        <div class="brand-mark">
                            <span class="bg-primary"></span>
                            <span class="bg-secondary"></span>
                            <span class="bg-danger"></span>
                        </div>
                        <span class="fw-semibold text-light">KANTUM</span>
                    </div>

                    <p class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 mb-4">Projectes sense estrès</p>

                    <div class="mb-4">
                        <h1 class="display-5 fw-semibold lh-sm text-white">
                            Organitza les idees.<br>
                            Avança en equip.
                        </h1>
                        <p class="intro-copy small text-secondary lh-lg mb-0">
                            Un espai clar i compartit perquè el teu equip se centri en allò que realment importa.
                        </p>
                    </div>

                    <ul class="feature-list d-grid gap-3 small mb-0">
                        <li class="d-flex align-items-center gap-3">
                            <span class="feature-check d-inline-flex align-items-center justify-content-center rounded bg-dark">✓</span>
                            Tasques clares, prioritats visibles
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <span class="feature-check d-inline-flex align-items-center justify-content-center rounded bg-dark">✓</span>
                            Col·laboració sense soroll
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <span class="feature-check d-inline-flex align-items-center justify-content-center rounded bg-dark">✓</span>
                            Progrés que motiva
                        </li>
                    </ul>
                </div>

                <p class="footer-note small text-secondary mb-0">Creat per a estudiants i equips que volen arribar més lluny.</p>
            </div>

            <div class="login-form-panel col-lg-6 d-flex flex-column justify-content-center gap-4 p-4 p-sm-5">
                <div>
                    <p class="eyebrow mb-3">Benvingut de nou</p>
                    <h2 class="h1 fw-semibold text-white mb-3">Torna al teu espai</h2>
                    <p class="small text-secondary mb-0">Introdueix les teves dades per continuar.</p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="d-grid gap-3">
                @csrf

                    <div>
                        <label for="email" class="form-label small text-uppercase text-secondary fw-semibold">Correu electrònic</label>
                        <input id="email" name="email" type="email" autocomplete="email" placeholder="arnau@kantum.cat" class="form-control form-control-lg" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <label for="password" class="form-label small text-uppercase text-secondary fw-semibold mb-2">Contrasenya</label>
                            <a href="#" class="forgot-link small mb-2">Has oblidat la contrasenya?</a>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••••••" class="form-control form-control-lg">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold text-white">
                        Entrar a Kantum <span aria-hidden="true">→</span>
                    </button>
                </form>

                <div class="d-grid gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="border-top border-secondary flex-grow-1"></span>
                        <span class="small text-secondary text-uppercase text-nowrap">O continua amb</span>
                        <span class="border-top border-secondary flex-grow-1"></span>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                                <span class="google-mark">G</span>
                                Google
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                                <span class="microsoft-mark" aria-hidden="true">
                                    <span></span><span></span><span></span><span></span>
                                </span>
                                Microsoft
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
