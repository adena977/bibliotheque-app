<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inscription - Bibliothèque de Djibouti</title>
    
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
        
        .register-container {
            width: 100%;
            max-width: 1200px;
            margin: 20px;
            position: relative;
            z-index: 1;
        }
        
        .register-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .register-card:hover {
            transform: translateY(-5px);
        }
        
        /* Section gauche - Branding (identique login) */
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
        
        /* Bouton d'inscription */
        .btn-register {
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
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(102, 126, 234, 0.4);
        }
        
        /* Lien de connexion */
        .login-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
        }
        
        .login-link p {
            font-size: 0.875rem;
            color: #718096;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        /* Alerte */
        .alert-custom {
            border-radius: 16px;
            border: none;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 0.875rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .brand-section {
                display: none;
            }
            .form-section {
                padding: 32px 24px;
            }
        }
        
        /* Animation fade-in */
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
        
        .register-card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="row g-0 register-card">
            <!-- Section gauche - Branding -->
            <div class="col-lg-6">
                <div class="brand-section">
                    <div class="logo-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1 class="brand-title">Rejoignez<br>notre communauté</h1>
                    <p class="brand-subtitle">
                        Créez votre compte gratuitement et accédez à tous les services de la bibliothèque : emprunts, réservations, suivi en ligne.
                    </p>
                    <ul class="feature-list">
                        <li class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check"></i></div>
                            <span>Inscription gratuite</span>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon"><i class="fas fa-infinity"></i></div>
                            <span>Accès illimité au catalogue</span>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon"><i class="fas fa-bell"></i></div>
                            <span>Notifications personnalisées</span>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon"><i class="fas fa-headset"></i></div>
                            <span>Support bibliothécaire dédié</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Section droite - Formulaire d'inscription -->
            <div class="col-lg-6">
                <div class="form-section">
                    <div class="form-header">
                        <h2 class="form-title">Créer un compte</h2>
                        <p class="form-subtitle">Remplissez le formulaire ci-dessous pour vous inscrire</p>
                    </div>
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-custom">
                            <i class="fas fa-exclamation-circle"></i> 
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="input-group-custom">
                            <label class="input-label"><i class="fas fa-user"></i> Nom complet</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" class="input-field @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus placeholder="Jean Dupont">
                            </div>
                        </div>
                        
                        <div class="input-group-custom">
                            <label class="input-label"><i class="fas fa-envelope"></i> Adresse email</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="input-field @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="exemple@bibliotheque.dj">
                            </div>
                        </div>
                        
                        <div class="input-group-custom">
                            <label class="input-label"><i class="fas fa-phone"></i> Téléphone (optionnel)</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-phone"></i></span>
                                <input type="tel" name="phone" class="input-field" value="{{ old('phone') }}" placeholder="+253 77 XX XX XX">
                            </div>
                        </div>
                        
                        <div class="input-group-custom">
                            <label class="input-label"><i class="fas fa-home"></i> Adresse (optionnel)</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea name="address" class="input-field" rows="2" placeholder="Votre adresse complète">{{ old('address') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="input-group-custom">
                            <label class="input-label"><i class="fas fa-lock"></i> Mot de passe</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="input-field @error('password') is-invalid @enderror" required placeholder="••••••••">
                            </div>
                        </div>
                        
                        <div class="input-group-custom">
                            <label class="input-label"><i class="fas fa-lock"></i> Confirmer le mot de passe</label>
                            <div class="input-wrapper">
                                <span class="input-icon"><i class="fas fa-check-circle"></i></span>
                                <input type="password" name="password_confirmation" class="input-field" required placeholder="••••••••">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-register">
                            <i class="fas fa-user-plus"></i> S'inscrire
                        </button>
                        
                        <div class="login-link">
                            <p>Déjà inscrit ? <a href="{{ route('login') }}">Connectez-vous</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Animation sur les champs (focus)
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