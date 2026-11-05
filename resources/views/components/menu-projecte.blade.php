<ul class="nav menu-projecte border-bottom border-secondary mb-4">
    <li class="nav-item">
        <a href="{{ route('tauler') }}" class="nav-link {{ request()->routeIs('tauler') ? 'active' : '' }}">Tauler</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('calendari') }}" class="nav-link {{ request()->routeIs('calendari') ? 'active' : '' }}">Calendari</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('analitiques') }}" class="nav-link {{ request()->routeIs('analitiques') ? 'active' : '' }}">Analítiques</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('projects.index') }}" class="nav-link">Tots els projectes</a>
    </li>
</ul>
