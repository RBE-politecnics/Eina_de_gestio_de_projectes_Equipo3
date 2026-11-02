@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <h1 class="text-white mb-4">Nuevo projecte</h1>
        @include('projects.form', ['action' => route('projects.store'), 'method' => 'POST', 'submitLabel' => 'Crear'])
    </div>
@endsection
