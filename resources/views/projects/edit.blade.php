@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <h1 class="text-white mb-4">Editar projecte</h1>
        @include('projects.form', ['action' => route('projects.update', $project), 'method' => 'PUT', 'submitLabel' => 'Guardar'])
    </div>
@endsection
