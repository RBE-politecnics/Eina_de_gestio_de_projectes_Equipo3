@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <a href="{{ route('projects.index') }}" class="btn btn-outline-light mb-4">Volver</a>
        <div class="card bg-secondary text-white p-4">
            <h1>{{ $project->nom }}</h1>
            <p class="mb-0">Color: {{ $project->Color }}</p>
        </div>
    </div>
@endsection
