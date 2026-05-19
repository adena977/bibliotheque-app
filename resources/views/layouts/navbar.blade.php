<nav class="navbar navbar-expand-lg navbar-light bg-light rounded mt-2">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h4">@yield('header', 'Tableau de bord')</span>
        
        <div class="d-flex">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-bell"></i>
                    @if(Auth::user()->notifications()->where('is_read', false)->count() > 0)
                        <span class="badge bg-danger">{{ Auth::user()->notifications()->where('is_read', false)->count() }}</span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @forelse(Auth::user()->notifications()->latest()->limit(5)->get() as $notif)
                        <li>
                            <a class="dropdown-item" href="#">
                                <small class="d-block">{{ $notif->title }}</small>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                    @empty
                        <li><span class="dropdown-item">Aucune notification</span></li>
                    @endforelse
                    <li><a class="dropdown-item text-primary" href="#">Voir toutes</a></li>
                </ul>
            </div>
            
            <div class="dropdown ms-3">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item text-muted">Rôle: {{ Auth::user()->role }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Déconnexion</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>