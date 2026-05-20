<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Bibliothèque de Djibouti</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animation de fond */
        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: bgMove 20s linear infinite;
            pointer-events: none;
        }
        
        @keyframes bgMove {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(40px, 40px) rotate(360deg); }
        }
        
        /* Conteneur principal */
        .login-container {
            width: 100%;
            max-width: 1200px;
            margin: 20px;
            position: relative;
            z-index: 1;
        }
        
        /* Carte principale */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        /* Section gauche - Branding */
        .brand-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 48px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .brand-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
        }
        
        .logo-icon {
            font-size: 4rem;
            color: #fff;
            margin-bottom: 24px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }
        
        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
            line-height: 1.2;
        }
        
        .brand-subtitle {
            font-size: 1rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.6;
        }
        
        .feature-list {
            margin-top: 40px;
            list-style: none;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }
        
        .feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }
        
        /* Section droite - Formulaire */
        .form-section {
            padding: 48px;
        }
        
        .form-header {
            margin-bottom: 32px;
        }
        
        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }
        
        .form-subtitle {
            font-size: 0.875rem;
            color: #718096;
        }
        
        /* Champs du formulaire */
        .input-group-custom {
            margin-bottom: 24px;
        }
        
        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .input-field {
            width: 100%;
            padding: 12px 16px 12px 48px;
            font-size: 0.95rem;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            background: #fff;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-field.is-invalid {
            border-color: #f56565;
        }
        
        /* Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: #4a5568;
            cursor: pointer;
        }
        
        .checkbox-label input {
            width: 16px;
            height: 16px;
            margin: 0;
            cursor: pointer;
        }
        
        .forgot-link {
            font-size: 0.875rem;
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #5a67d8;
            text-decoration: underline;
        }
        
        /* Bouton de connexion */
        .btn-login {
            width: 100%;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        /* Lien d'inscription */
        .register-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }
        
        .register-link p {
            font-size: 0.875rem;
            color: #718096;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .register-link a:hover {
            color: #5a67d8;
            text-decoration: underline;
        }
        
        /* Cartes de test */
        .test-cards {
            margin-top: 24px;
            background: #f7fafc;
            border-radius: 16px;
            padding: 16px;
        }
        
        .test-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #a0aec0;
            margin-bottom: 12px;
        }
        
        .test-credentials {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .test-item {
            flex: 1;
            background: #fff;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .test-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .test-role {
            font-size: 0.7rem;
            font-weight: 600;
            color: #667eea;
        }
        
        .test-email {
            font-size: 0.7rem;
            color: #4a5568;
            font-family: monospace;
        }
        
        /* Alertes */
        .alert-custom {
            border-radius: 16px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 0.875rem;
        }
        
        .alert-custom i {
            margin-right: 8px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .brand-section {
                display: none;
            }
            
            .form-section {
                padding: 32px 24px;
            }
            
            .test-credentials {
                flex-direction: column;
            }
        }
        
        /* Animation d'entrée */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="row g-0 login-card">
            <!-- Section gauche - Branding -->
            <div class="col-lg-6">
                <div class="brand-section">
                    <div class="logo-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h1 class="brand-title">Bibliothèque<br>de Djibouti</h1>
                    <p class="brand-subtitle">
                        Accédez à votre espace personnel pour gérer vos emprunts, 
                        réservations et consulter le catalogue de la bibliothèque.
                    </p>
                    
                    <ul class="feature-list">
                        <li class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <span>Recherche avancée de livres</span>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-hand-holding-heart"></i>
                            </div>
                            <span>Emprunts et réservations en ligne</span>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span>Suivi des amendes et retards</span>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <span>Notifications en temps réel</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Section droite - Formulaire -->
            <div class="col-lg-6">
                <div class="form-section">
                    <div class="form-header">
                        <h2 class="form-title">Bienvenue</h2>
                        <p class="form-subtitle">Connectez-vous pour accéder à votre compte</p>
                    </div>
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        
                        <div class="input-group-custom">
                            <label class="input-label">
                                <i class="fas fa-envelope"></i> Adresse email
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="input-field @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required autofocus placeholder="exemple@bibliotheque.dj">
                            </div>
                            @error('email')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="input-group-custom">
                            <label class="input-label">
                                <i class="fas fa-lock"></i> Mot de passe
                            </label>
                            <div class="input-wrapper">
                                <span class="input-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" class="input-field @error('password') is-invalid @enderror" 
                                       required placeholder="••••••••">
                            </div>
                            @error('password')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="form-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>Se souvenir de moi</span>
                            </label>
                            <a href="#" class="forgot-link">Mot de passe oublié ?</a>
                        </div>
                        
                        <button type="submit" class="btn-login">
                            <i class="fas fa-arrow-right-to-bracket"></i> Se connecter
                        </button>
                        
                        <div class="register-link">
                            <p>Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p>
                        </div>
                        
                        <!-- Comptes de test (démo) -->
                        <div class="test-cards">
                            <div class="test-title">
                                <i class="fas fa-flask"></i> COMPTES DE DÉMONSTRATION
                            </div>
                            <div class="test-credentials">
                                <div class="test-item" onclick="fillCredentials('admin@bibliotheque.com', 'admin123')">
                                    <div class="test-role">👑 ADMINISTRATEUR</div>
                                    <div class="test-email">admin@bibliotheque.com</div>
                                </div>
                                <div class="test-item" onclick="fillCredentials('librarian@bibliotheque.com', 'lib123')">
                                    <div class="test-role">📚 BIBLIOTHÉCAIRE</div>
                                    <div class="test-email">librarian@bibliotheque.com</div>
                                </div>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Fonction pour remplir automatiquement les identifiants
        function fillCredentials(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
            
            // Effet visuel
            const btn = event.currentTarget;
            btn.style.transform = 'scale(0.98)';
            setTimeout(() => {
                btn.style.transform = '';
            }, 200);
            
            // Optionnel : auto-submit
            // document.getElementById('loginForm').submit();
        }
        
        // Animation de focus sur les champs
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#667eea';
            });
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#a0aec0';
            });
        });
    </script>
</body>
</html>