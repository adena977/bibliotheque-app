<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Bibliothèque')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- PWA Meta Tags -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#2b8c5e">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Bibliothèque">
<link rel="apple-touch-icon" href="{{ asset('images/icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icon-192x192.png') }}">
<link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/icon-512x512.png') }}">

<!-- Service Worker -->
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            console.log('Service Worker enregistré avec succès:', registration.scope);
        }).catch(function(error) {
            console.log('Erreur d\'enregistrement du Service Worker:', error);
        });
    });
}
</script>

<!-- Installation de l'application -->
<style>
    .install-banner {
        position: fixed;
        bottom: 20px;
        left: 20px;
        right: 20px;
        background: linear-gradient(135deg, #1e5799, #2b8c5e);
        border-radius: 16px;
        padding: 16px;
        color: white;
        display: none;
        z-index: 1000;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        animation: slideUp 0.5s ease;
    }
    
    @keyframes slideUp {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .install-banner button {
        background: white;
        border: none;
        padding: 10px 20px;
        border-radius: 30px;
        margin-top: 10px;
        font-weight: 600;
        color: #2b8c5e;
        cursor: pointer;
    }
    
    @media (display-mode: standalone) {
        .install-banner {
            display: none !important;
        }
    }
</style>

<div id="installBanner" class="install-banner">
    <div style="display: flex; align-items: center; gap: 15px;">
        <i class="fas fa-download" style="font-size: 2rem;"></i>
        <div style="flex: 1;">
            <strong>Installer l'application</strong><br>
            <small>Installez Bibliothèque sur votre appareil</small>
        </div>
        <button id="installApp">Installer</button>
    </div>
</div>

<script>
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    document.getElementById('installBanner').style.display = 'block';
    
    document.getElementById('installApp').addEventListener('click', () => {
        document.getElementById('installBanner').style.display = 'none';
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('Utilisateur a accepté l\'installation');
            }
            deferredPrompt = null;
        });
    });
});
</script>
  <style>
        body {
            overflow-x: hidden;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            z-index: 1000;
        }
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    @stack('styles')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap');
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Inter', sans-serif;
        background: #f5f7fb;
        margin: 0;
        overflow-x: hidden;
    }
    .main-content {
        margin-left: 280px;
        transition: margin-left 0.3s;
        min-height: 100vh;
        background: #f5f7fb;
        padding: 20px 30px;
    }
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 15px;
        }
    }
</style>
</head>
<body>
    @include('layouts.sidebar')
    
    <div class="main-content">
        @include('layouts.navbar')
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>