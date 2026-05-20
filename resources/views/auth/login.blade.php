<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - Bibliothèque de Djibouti</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: url('{{ asset("images/bibliotheque-bg.jpg") }}') no-repeat center center fixed;
            background-size: cover;
        }

        /* Overlay moderne */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.6) 100%);
            backdrop-filter: blur(8px);
            z-index: 0;
        }

        /* Conteneur principal */
        .login-wrapper {
            width: 100%;
            max-width: 1300px;
            margin: 20px;
            position: relative;
            z-index: 1;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Carte principale */
        .login-card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 50px 90px rgba(0, 0, 0, 0.4);
        }

        /* Section illustration */
        .illustration-section {
            background: linear-gradient(135deg, #1e5799 0%, #2b8c5e 100%);
            padding: 60px 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .illustration-section::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -20%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: bgMove 30s linear infinite;
        }

        @keyframes bgMove {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(60px, 60px) rotate(360deg); }
        }

        .illustration-icon {
            font-size: 5rem;
            color: #fff;
            margin-bottom: 30px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .illustration-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .illustration-text {
            font-size: 1rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .stats-grid {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }

        .stat-item {
            flex: 1;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            display: block;
        }

        .stat-label {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.8);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Section formulaire */
        .form-section {
            padding: 50px 45px;
            background: #fff;
        }

        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .form-logo {
            font-size: 2.5rem;
            color: #2b8c5e;
            margin-bottom: 15px;
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

        /* Groupes de champs modernes */
        .input-group-modern {
            margin-bottom: 25px;
        }

        .input-group-modern label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .input-group-modern .input-wrapper {
            position: relative;
        }

        .input-group-modern .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e0;
            font-size: 1.1rem;
            transition: color 0.3s;
        }

        .input-group-modern input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            font-size: 0.95rem;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            background: #f8fafc;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .input-group-modern input:focus {
            outline: none;
            border-color: #2b8c5e;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(43, 140, 94, 0.1);
        }

        .input-group-modern input.is-invalid {
            border-color: #f56565;
        }

        /* Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-custom {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            color: #4a5568;
        }

        .checkbox-custom input {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #2b8c5e;
        }

        .forgot-password {
            font-size: 0.85rem;
            color: #2b8c5e;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #1e3c72;
            text-decoration: underline;
        }

        /* Bouton */
        .btn-login-modern {
            width: 100%;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #1e5799 0%, #2b8c5e 100%);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-login-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login-modern:hover::before {
            left: 100%;
        }

        .btn-login-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(43, 140, 94, 0.4);
        }

        /* Comptes de démonstration */
        .demo-section {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }

        .demo-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #a0aec0;
            text-align: center;
            margin-bottom: 15px;
        }

        .demo-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .demo-btn {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            padding: 10px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .demo-btn:hover {
            border-color: #2b8c5e;
            background: #e8f5e9;
            transform: translateY(-2px);
        }

        .demo-role {
            font-size: 0.7rem;
            font-weight: 600;
            color: #2b8c5e;
            display: block;
        }

        .demo-email {
            font-size: 0.65rem;
            color: #4a5568;
            font-family: monospace;
        }

        /* Alertes */
        .alert-modern {
            border-radius: 20px;
            border: none;
            padding: 12px 18px;
            margin-bottom: 25px;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .illustration-section {
                display: none;
            }
            .form-section {
                padding: 35px 25px;
            }
            .demo-buttons {
                flex-direction: column;
            }
            .form-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="row g-0 login-card">
            <!-- Section gauche - Illustration -->
            <div class="col-lg-6">
                <div class="illustration-section">
                    <div class="illustration-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <h2 class="illustration-title">Bienvenue à la<br>Bibliothèque de Djibouti</h2>
                    <p class="illustration-text">
                        Découvrez notre catalogue numérique, empruntez vos livres préférés, 
                        et gérez vos retours en toute simplicité.
                    </p>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-number">10k+</span>
                            <span class="stat-label">Livres</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">3k+</span>
                            <span class="stat-label">Membres</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Accès</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section droite - Formulaire -->
            <div class="col-lg-6">
                <div class="form-section">
                    <div class="form-header">
                        <div class="form-logo">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="form-title">Connexion</h3>
                        <p class="form-subtitle">Accédez à votre espace personnel</p>
                    </div>
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-modern">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-modern">
                            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="input-group-modern">
                            <label>Adresse email</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" value="{{ old('email') }}" 
                                       placeholder="votre.email@bibliotheque.dj" required autofocus>
                            </div>
                        </div>
                        
                        <div class="input-group-modern">
                            <label>Mot de passe</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <label class="checkbox-custom">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>Se souvenir de moi</span>
                            </label>
                            <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                        </div>
                        
                        <button type="submit" class="btn-login-modern">
                            <i class="fas fa-arrow-right-to-bracket"></i> Se connecter
                        </button>
                        
                        <div class="demo-section">
                            <div class="demo-title">
                                <i class="fas fa-flask"></i> COMPTES DE DÉMONSTRATION
                            </div>
                            <div class="demo-buttons">
                                <div class="demo-btn" onclick="fillCredentials('admin@bibliotheque.com', 'admin123')">
                                    <span class="demo-role">👑 ADMINISTRATEUR</span>
                                    <span class="demo-email">admin@bibliotheque.com</span>
                                </div>
                                <div class="demo-btn" onclick="fillCredentials('librarian@bibliotheque.com', 'lib123')">
                                    <span class="demo-role">📚 BIBLIOTHÉCAIRE</span>
                                    <span class="demo-email">librarian@bibliotheque.com</span>
                                </div>
                                <div class="demo-btn" onclick="fillCredentials('member@bibliotheque.com', 'member123')">
                                    <span class="demo-role">👤 MEMBRE</span>
                                    <span class="demo-email">member@bibliotheque.com</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function fillCredentials(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
            
            // Animation visuelle
            const btn = event.currentTarget;
            btn.style.transform = 'scale(0.96)';
            setTimeout(() => {
                btn.style.transform = '';
            }, 200);
        }
    </script>
</body>
</html>