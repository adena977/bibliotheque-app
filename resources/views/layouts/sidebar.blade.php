<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo-wrapper">
            <i class="fas fa-book-open"></i>
            <span>Bibliothèque</span>
        </div>
        <div class="user-info">
            @if(Auth::check())
                <div class="avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="user-details">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
            @else
                <div class="avatar">👤</div>
                <div class="user-details">
                    <span class="user-name">Invité</span>
                </div>
            @endif
        </div>
    </div>

    <nav class="sidebar-nav">
        @if(Auth::check())
            <!-- Menu commun à tous les connectés -->
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="{{ route('books.index') }}" class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span>Catalogue</span>
            </a>

            @if(Auth::user()->isMember())
                <a href="{{ route('my.borrowings') }}" class="nav-item {{ request()->routeIs('my.borrowings') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding"></i>
                    <span>Mes emprunts</span>
                </a>
                <a href="{{ route('my.reservations') }}" class="nav-item {{ request()->routeIs('my.reservations') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Mes réservations</span>
                </a>
                <a href="{{ route('fines.index') }}" class="nav-item {{ request()->routeIs('fines.*') ? 'active' : '' }}">
                    <i class="fas fa-coins"></i>
                    <span>Mes amendes</span>
                </a>
            @endif

            @if(Auth::user()->isLibrarian())
                <div class="nav-divider">Gestion</div>
                <a href="{{ route('librarian.books.index') }}" class="nav-item">
                    <i class="fas fa-book-open"></i>
                    <span>Livres</span>
                </a>
                <a href="{{ route('librarian.borrowings.index') }}" class="nav-item">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Emprunts</span>
                </a>
                <a href="{{ route('librarian.members.index') }}" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Membres</span>
                </a>
            @endif

            @if(Auth::user()->isAdmin())
                <div class="nav-divider">Administration</div>
                <a href="{{ route('admin.users.index') }}" class="nav-item">
                    <i class="fas fa-user-shield"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Rapports</span>
                </a>
                <a href="{{ route('admin.settings') }}" class="nav-item">
                    <i class="fas fa-sliders-h"></i>
                    <span>Paramètres</span>
                </a>
            @endif
        @endif
    </nav>

    @if(Auth::check())
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    @else
        <div class="sidebar-footer">
            <a href="{{ route('login') }}" class="login-btn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Connexion</span>
            </a>
            <a href="{{ route('register') }}" class="register-btn">
                <i class="fas fa-user-plus"></i>
                <span>Inscription</span>
            </a>
        </div>
    @endif
</aside>

<style>
    /* SIDEBAR STYLES */
    .sidebar {
        width: 280px;
        background: linear-gradient(145deg, #1a1f2e 0%, #141824 100%);
        color: #e0e4f0;
        display: flex;
        flex-direction: column;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 100;
        box-shadow: 5px 0 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    .sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .logo-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.4rem;
        font-weight: 700;
        color: white;
        margin-bottom: 28px;
    }

    .logo-wrapper i {
        font-size: 1.8rem;
        background: linear-gradient(135deg, #a78bfa, #3b82f6);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.05);
        padding: 12px;
        border-radius: 20px;
    }

    .avatar {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #a78bfa, #3b82f6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        text-transform: uppercase;
        color: white;
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
        color: white;
    }

    .user-role {
        display: block;
        font-size: 0.7rem;
        opacity: 0.7;
        text-transform: capitalize;
    }

    .sidebar-nav {
        flex: 1;
        padding: 20px 12px;
        overflow-y: auto;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        margin: 4px 0;
        border-radius: 14px;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.95rem;
    }

    .nav-item i {
        width: 24px;
        font-size: 1.2rem;
    }

    .nav-item:hover {
        background: rgba(255,255,255,0.08);
        color: white;
    }

    .nav-item.active {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: white;
        box-shadow: 0 6px 14px rgba(59,130,246,0.3);
    }

    .nav-divider {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 16px 16px 8px;
        color: #6b7280;
    }

    .sidebar-footer {
        padding: 20px;
        border-top: 1px solid rgba(255,255,255,0.08);
    }

    .logout-btn, .login-btn, .register-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 10px;
        border-radius: 40px;
        border: none;
        background: rgba(255,255,255,0.08);
        color: #f0f0f0;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
    }

    .logout-btn:hover, .login-btn:hover {
        background: rgba(239,68,68,0.2);
        color: #f87171;
    }

    .register-btn {
        margin-top: 10px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border: none;
        color: white;
    }

    /* Scrollbar personnalisée */
    .sidebar-nav::-webkit-scrollbar {
        width: 4px;
    }
    .sidebar-nav::-webkit-scrollbar-track {
        background: #1e2436;
    }
    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            width: 260px;
        }
        .sidebar.mobile-open {
            transform: translateX(0);
        }
        .main-content {
            margin-left: 0 !important;
        }
    }
</style>

<script>
    // Pour mobile : ajouter un bouton de toggle
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.createElement('button');
        btn.innerHTML = '<i class="fas fa-bars"></i>';
        btn.className = 'mobile-menu-toggle';
        btn.style.position = 'fixed';
        btn.style.bottom = '20px';
        btn.style.right = '20px';
        btn.style.zIndex = '1000';
        btn.style.width = '50px';
        btn.style.height = '50px';
        btn.style.borderRadius = '50%';
        btn.style.background = '#3b82f6';
        btn.style.border = 'none';
        btn.style.color = 'white';
        btn.style.fontSize = '1.5rem';
        btn.style.cursor = 'pointer';
        btn.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
        document.body.appendChild(btn);
        btn.addEventListener('click', () => {
            document.querySelector('.sidebar').classList.toggle('mobile-open');
        });
    });
</script>