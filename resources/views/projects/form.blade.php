<form method="POST" action="{{ $action }}" class="bg-secondary rounded p-4">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label for="nom" class="form-label text-white">Nom</label>
        <input id="nom" name="nom" type="text" class="form-control" value="{{ old('nom', $project->nom ?? '') }}" required>
        @error('nom')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="Color" class="form-label text-white">Color</label>
        <input id="Color" name="Color" type="color" class="form-control form-control-color" value="{{ old('Color', $project->Color ?? '#FFFFFF') }}">
        @error('Color')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ route('projects.index') }}" class="btn btn-outline-light">Cancelar</a>
</form>
