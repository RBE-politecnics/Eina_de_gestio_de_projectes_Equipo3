@extends('layouts.main')

@section('content')
    <div class="container-fluid bg-dark min-vh-100">
        <div class="row min-vh-100">
            @include('components.sidebar')

            <main class="col-12 col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="text-white">Projectes</h1>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary">Nou projecte</a>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="row g-4">
                    @forelse ($projects as $project)
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card bg-secondary text-white h-100 p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="rounded-circle" style="width: 14px; height: 14px; background-color: {{ $project->Color }}"></span>
                                    <h2 class="h5 mb-0">{{ $project->nom }}</h2>
                                </div>
                                <div class="d-flex gap-2 mt-auto">
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-light btn-sm">Ver</a>
                                    <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-light btn-sm">Editar</a>
                                    <form method="POST" action="{{ route('projects.destroy', $project) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-white">No hay proyectos todavía.</p>
                    @endforelse
                </div>
            </main>
        </div>
    </div>
@endsection
