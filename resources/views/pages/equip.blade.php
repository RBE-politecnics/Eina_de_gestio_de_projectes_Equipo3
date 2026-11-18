@extends('layouts.main')

@section('content')
    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">

            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">

                <div class="kt-capcalera">
                    <div>
                        <h1>Equip del projecte</h1>
                        <p class="text-secondary small mb-0">Gestiona els col·laboradors i els seus rols.</p>
                    </div>

                    @if ($projecte && $esAdmin)
                        <button type="button" class="btn btn-primary px-4 py-2"
                                data-bs-toggle="modal" data-bs-target="#afegirModal">
                            + Afegir col·laborador
                        </button>
                    @endif
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                @if (! $projecte)
                    <div class="kt-targeta text-center text-secondary">
                        Encara no participes en cap projecte.
                    </div>
                @else
                    <form method="GET" action="{{ route('equip') }}" class="mb-3">
                        <label for="projecte" class="form-label text-secondary small">Projecte</label>
                        <select id="projecte" name="projecte" class="form-select w-auto" onchange="this.form.submit()">
                            @foreach ($projectes as $opcio)
                                <option value="{{ $opcio->id }}" @selected($opcio->id == $projecte->id)>{{ $opcio->nom }}</option>
                            @endforeach
                        </select>
                    </form>

                    <div class="kt-targeta">
                        <div class="table-responsive">
                            <table class="table kt-taula">
                                <thead>
                                    <tr>
                                        <th>Col·laborador</th>
                                        <th>Correu electrònic</th>
                                        <th>Rol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($membres as $membre)
                                        <tr>
                                            <td>
                                                <span class="kt-avatar me-2">{{ substr($membre->email, 0, 2) }}</span>
                                                <span class="fw-bold">{{ ucfirst(explode('@', $membre->email)[0]) }}</span>
                                            </td>
                                            <td class="text-secondary">{{ $membre->email }}</td>
                                            <td>
                                                <span @class(['kt-rol', 'admin' => $membre->rol == 'Administrador'])>{{ $membre->rol }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-secondary small mt-3 mb-0">{{ $membres->count() }} col·laboradors</p>
                    </div>
                @endif
            </main>

        </div>
    </div>

    @if ($projecte && $esAdmin)
        <div class="modal" id="afegirModal" tabindex="-1" aria-labelledby="afegirModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-secondary text-white">

                    <div class="modal-header">
                        <h5 class="modal-title" id="afegirModalLabel">Afegir col·laborador</h5>
                        <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal" aria-label="Tancar"></button>
                    </div>

                    <form method="POST" action="{{ route('equip.afegir') }}">
                        @csrf
                        <input type="hidden" name="projecte_id" value="{{ $projecte->id }}">

                        <div class="modal-body">
                            <label for="email-membre" class="form-label">Correu electrònic</label>
                            <input type="email" id="email-membre" name="email" class="form-control"
                                   placeholder="nom@correu.cat" value="{{ old('email') }}" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Tancar</button>
                            <button type="submit" class="btn btn-primary">Afegir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
