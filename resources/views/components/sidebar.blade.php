<aside class="col-12 col-md-3 col-lg-2 bg-secondary text-white p-3 dashboard-sidebar d-flex flex-column min-vh-100">
    <div class="d-flex align-items-center justify-content-center gap-2 mb-4">
        <img src="{{ Vite::asset('resources/assets/logo.png') }}" alt="" width="32" height="32" class="rounded" aria-hidden="true">
        <h2 class="h5 mb-0">KANTUM</h2>
    </div>

    <nav class="nav nav-pills flex-column gap-1">
        <a href="{{ route('dashboard') }}" class="nav-link active">
            Proyecto
        </a>
        <a href="{{ route('projects.index') }}" class="nav-link text-white">
            Projectes
        </a>
        <a href="{{ route('roles') }}" class="nav-link text-white">
            Rols
        </a>
        <a href="#" class="nav-link text-white">
            Tasques
        </a>
    </nav>

    <footer class="mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100">
                Tancar sessió
            </button>
        </form>
    </footer>

</aside>