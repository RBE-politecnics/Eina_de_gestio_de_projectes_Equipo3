<aside class="col-12 col-md-3 col-lg-2 text-white kt-sidebar min-vh-100">
    <a href="{{ route('dashboard') }}" class="kt-marca">
        <img src="{{ Vite::asset('resources/assets/logo-kantum.png') }}" alt="">
        <span>KANTUM</span>
    </a>

    <nav class="kt-menu">
        <p class="kt-etiqueta">Espai de treball</p>

        <a href="{{ route('dashboard') }}" @class(['kt-enllac', 'actiu' => request()->routeIs('dashboard')])>
            Dashboard
        </a>
        <a href="{{ route('projectes') }}" @class(['kt-enllac', 'actiu' => request()->routeIs('projectes')])>
            Els meus projectes
        </a>
        <a href="{{ route('tauler') }}" @class(['kt-enllac', 'actiu' => request()->routeIs('tauler')])>
            Tauler
        </a>
        <a href="{{ route('calendari') }}" @class(['kt-enllac', 'actiu' => request()->routeIs('calendari')])>
            Calendari
        </a>
        <a href="{{ route('analitiques') }}" @class(['kt-enllac', 'actiu' => request()->routeIs('analitiques')])>
            Analítiques
        </a>
        <a href="{{ route('equip') }}" @class(['kt-enllac', 'actiu' => request()->routeIs('equip')])>
            Equip
        </a>
    </nav>

    <footer class="kt-usuari">
        <span class="kt-avatar">{{ substr(auth()->user()->email, 0, 2) }}</span>
        <span class="kt-usuari-correu">{{ auth()->user()->email }}</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="kt-sortir">Sortir</button>
        </form>
    </footer>
</aside>
