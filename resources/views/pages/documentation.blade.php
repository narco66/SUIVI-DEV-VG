@extends('layouts.public')

@section('content')
<section class="bg-primary text-white pt-5 pb-5" style="background: linear-gradient(135deg, var(--ceeac-blue) 0%, #004085 100%);">
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">Accueil</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Documentation</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold mb-3">Documentation & Guides</h1>
                <p class="lead opacity-75 mb-0">Manuels d'utilisation et ressources méthodologiques pour la prise en main de la plateforme.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-light">
    <div class="container">
        
        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-book fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Manuel de l'Utilisateur</h5>
                        <p class="text-muted small mb-4">Guide complet détaillant comment se connecter, naviguer, ajouter une décision, créer un plan d'action et assigner des activités.</p>
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3" disabled><i class="bi bi-download me-1"></i> Bientôt disponible</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-diagram-2 fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Guide de Planification</h5>
                        <p class="text-muted small mb-4">Méthodologie CEEAC de découpage d'une décision macroscopique en plans d'action logiques, avec définition d'indicateurs SMART.</p>
                        <button class="btn btn-outline-success btn-sm rounded-pill px-3" disabled><i class="bi bi-download me-1"></i> Bientôt disponible</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 70px; height: 70px;">
                            <i class="bi bi-check-all fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Guide Validateur</h5>
                        <p class="text-muted small mb-4">Destiné aux Directeurs et Commissaires pour comprendre les workflows de validation des réalisations et étapes d'un rapport de terrain.</p>
                        <button class="btn btn-outline-warning text-dark btn-sm rounded-pill px-3" disabled><i class="bi bi-download me-1"></i> Bientôt disponible</button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection
