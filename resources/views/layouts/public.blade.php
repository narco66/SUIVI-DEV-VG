<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Plateforme institutionnelle de suivi de l'exécution des décisions communautaires de la CEEAC">
    <title>SUIVI-DEC | Commission de la CEEAC</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            /* Couleurs institutionnelles CEEAC (Simulées) */
            --ceeac-blue: #003366;      /* Bleu foncé institutionnel */
            --ceeac-blue-light: #0056b3; /* Bleu secondaire */
            --ceeac-gray-light: #f8f9fa;
            --ceeac-gray-dark: #343a40;
            --ceeac-accent: #f2a900;     /* Touche dorée / jaune institutionnel éventuelle */
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            background-color: var(--ceeac-gray-light);
            -webkit-font-smoothing: antialiased;
        }

        /* Navbar Custom */
        .navbar-institution {
            background-color: #ffffff;
            border-bottom: 2px solid var(--ceeac-blue);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        .navbar-brand-text {
            color: var(--ceeac-blue);
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .navbar-brand-subtitle {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .nav-link {
            font-weight: 500;
            color: var(--ceeac-gray-dark);
            margin: 0 0.25rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--ceeac-blue-light);
            background-color: rgba(0, 86, 179, 0.05);
            border-radius: 4px;
        }

        /* Buttons */
        .btn-ceeac-primary {
            background-color: var(--ceeac-blue);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.6rem 1.5rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        
        .btn-ceeac-primary:hover {
            background-color: var(--ceeac-blue-light);
            color: white;
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--ceeac-blue) 0%, var(--ceeac-blue-light) 100%);
            color: white;
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('https://www.transparenttextures.com/patterns/cubes.png') opacity(0.05);
            pointer-events: none;
        }

        /* Sections */
        .section-padding {
            padding: 5rem 0;
        }
        
        .section-title {
            color: var(--ceeac-blue);
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 3rem;
        }

        /* Cards */
        .card-module {
            border: none;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .card-module:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.08);
            border-bottom: 4px solid var(--ceeac-blue);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background-color: rgba(0, 86, 179, 0.1);
            color: var(--ceeac-blue);
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }

        /* Footer */
        .footer-institution {
            background-color: var(--ceeac-gray-dark);
            color: rgba(255,255,255,0.8);
            padding: 4rem 0 2rem;
        }
        
        .footer-institution h5 {
            color: white;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.2s ease;
        }
        
        .footer-links a:hover {
            color: white;
            text-decoration: underline;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 1.5rem;
            margin-top: 3rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-institution sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <!-- Placeholder Logo CEEAC -->
                <div class="me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: var(--ceeac-blue); border-radius: 50%; color: white; font-weight: bold;">
                    CEEAC
                </div>
                <div>
                    <h1 class="navbar-brand-text h4 mb-0">SUIVI-DEC</h1>
                    <span class="navbar-brand-subtitle">Commission de la CEEAC</span>
                </div>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarPublic">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('plateforme') ? 'active' : '' }}" href="{{ url('/plateforme') }}">La Plateforme</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('modules') ? 'active' : '' }}" href="{{ url('/modules') }}">Modules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('instances') ? 'active' : '' }}" href="{{ url('/instances') }}">Instances</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('rapports') ? 'active' : '' }}" href="{{ url('/rapports') }}">Rapports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('documentation') ? 'active' : '' }}" href="{{ url('/documentation') }}">Documentation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('a-propos') ? 'active' : '' }}" href="{{ url('/a-propos') }}">À propos</a>
                    </li>
                </ul>
                <div class="d-flex">
                    @auth
                        <a href="{{ url('/home') }}" class="btn btn-ceeac-primary rounded-pill px-4">Accéder au portail</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ceeac-primary rounded-pill px-4"><i class="bi bi-box-arrow-in-right me-2"></i> Connexion</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-institution">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.1); border-radius: 50%; color: white; border: 1px solid rgba(255,255,255,0.2);">
                            CEEAC
                        </div>
                        <h5 class="mb-0">Commission de la CEEAC</h5>
                    </div>
                    <p class="small text-muted mb-4 pe-lg-4">
                        La plateforme SUIVI-DEC permet d'assurer un suivi transparent et rigoureux de l'exécution des décisions communautaires par les États membres et les instances de la CEEAC.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height:35px;"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height:35px;"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-white bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height:35px;"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 footer-links">
                    <h5>Navigation</h5>
                    <a href="{{ url('/') }}">Accueil</a>
                    <a href="{{ url('/plateforme') }}">La Plateforme</a>
                    <a href="{{ url('/modules') }}">Modules</a>
                    <a href="{{ url('/instances') }}">Instances</a>
                </div>
                
                <div class="col-lg-3 col-md-6 footer-links">
                    <h5>Ressources</h5>
                    <a href="{{ url('/rapports') }}">Rapports Stratégiques</a>
                    <a href="{{ url('/documentation') }}">Guide d'utilisation</a>
                    <a href="#">Foire Aux Questions</a>
                    <a href="#">Support Technique</a>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5>Contact</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2 text-white"></i> Libreville, République Gabonaise</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2 text-white"></i> support-suividec@ceeac-eccas.org</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2 text-white"></i> +241 00 00 00 00</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="mb-2 mb-md-0">&copy; {{ date('Y') }} Commission de la CEEAC. Tous droits réservés.</p>
                <div class="footer-links d-flex gap-3 mb-0">
                    <a href="#" class="mb-0">Mentions légales</a>
                    <span class="text-muted">|</span>
                    <a href="#" class="mb-0">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Alpine.js (Optional for lightweight interactivity) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
