<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SUIVI-DEC') }} - CEEAC</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/custom.css'])
</head>
<body class="bg-light">
    <div id="app" class="d-flex wrapper">
        @auth
            <nav id="sidebar" class="bg-ceeac-blue text-white shadow-sm min-vh-100">
                <div class="sidebar-header p-4 border-bottom border-light border-opacity-25 d-flex align-items-center">
                    <i class="bi bi-globe-africa fs-3 me-2"></i>
                    <h4 class="mb-0 fw-bold tracking-wide">SUIVI-DEC</h4>
                </div>

                <ul class="list-unstyled components p-3">
                    <p class="text-uppercase fw-semibold text-white-50 small mb-2 px-2">Pilotage</p>
                    <li class="nav-item mb-1">
                        <a href="{{ url('/home') }}" class="nav-link text-white rounded d-flex align-items-center py-2 px-3 {{ request()->is('home') ? 'active-link' : '' }}">
                            <i class="bi bi-grid-1x2-fill me-3"></i> Tableaux de bord
                        </a>
                    </li>
                    <li class="nav-item mb-1 mt-2">
                        <a href="{{ route('ai-analyses.create') }}" class="nav-link bg-primary bg-opacity-25 text-white rounded d-flex align-items-center py-2 px-3 border border-primary border-opacity-50 hover-elevate transition-all {{ request()->routeIs('ai-analyses.*') ? 'active-link fw-bold' : '' }}">
                            <i class="bi bi-robot text-info me-3"></i> 
                            <span>Assistant IA <span class="badge bg-info text-dark ms-2" style="font-size: 0.65rem;">NOUVEAU</span></span>
                        </a>
                    </li>

                    <p class="text-uppercase fw-semibold text-white-50 small mb-2 mt-4 px-2">Planification et Suivi</p>
                    <li class="nav-item mb-2">
                        <a href="{{ route('action-plans.index') }}" class="nav-link text-white rounded d-flex align-items-center py-2 px-3 {{ request()->routeIs('action-plans.*') ? 'active-link' : '' }}">
                            <i class="bi bi-diagram-3 me-3"></i> Plans d'action
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('progress-updates.index') }}" class="nav-link text-white rounded d-flex align-items-center py-2 px-3 {{ request()->routeIs('progress-updates.*') ? 'active-link' : '' }}">
                            <i class="bi bi-graph-up-arrow me-3"></i> Mises à jour
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('validations.index') }}" class="nav-link text-white rounded d-flex align-items-center py-2 px-3 {{ request()->routeIs('validations.*') ? 'active-link' : '' }}">
                            <i class="bi bi-shield-check me-3"></i> Approbations
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('reports.index') }}" class="nav-link text-white rounded d-flex align-items-center py-2 px-3 {{ request()->routeIs('reports.*') ? 'active-link' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph me-3"></i> Rapports Stratégiques
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('ged.index') }}" class="nav-link text-white rounded d-flex align-items-center py-2 px-3 {{ request()->routeIs('ged.*') ? 'active-link' : '' }}">
                            <i class="bi bi-folder2-open me-3"></i> GED
                        </a>
                    </li>
                    
                    <p class="text-uppercase fw-semibold text-white-50 small mb-2 mt-4 px-2">Administration</p>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.index') }}" class="nav-link text-white rounded d-flex align-items-center py-2 px-3 {{ request()->routeIs('admin.*') || request()->routeIs('decision-types.*') || request()->routeIs('decision-categories.*') || request()->routeIs('domains.*') || request()->routeIs('sessions.*') ? 'active-link' : '' }}">
                            <i class="bi bi-gear-fill me-3"></i> Paramètres
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('manual.download') }}" class="nav-link text-white rounded d-flex align-items-center py-2 px-3">
                            <i class="bi bi-file-earmark-text me-3"></i> Manuel Utilisateur
                        </a>
                    </li>
                </ul>
            </nav>
        @endauth

        <div id="content" class="w-100 bg-light">
            <nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm px-4 py-3">
                <div class="container-fluid p-0">
                    @auth
                        <button type="button" id="sidebarCollapse" class="btn btn-light d-lg-none me-3">
                            <i class="bi bi-list"></i>
                        </button>
                    @endauth

                    <span class="navbar-brand fw-semibold text-ceeac-blue mb-0">
                        Commission de la CEEAC
                    </span>

                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link fw-medium text-dark" href="{{ route('login') }}">Connexion</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center text-dark fw-medium" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <div class="bg-ceeac-blue text-white rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="p-4 p-md-5 overflow-auto">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
