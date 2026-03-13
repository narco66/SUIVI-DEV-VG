@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-gear-fill fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Administration</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Paramètres et Administration</h2>
                        <p class="text-muted mb-2">Gestion centralisée des données de référence de l'application.</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">Centre de configuration</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paramètres Généraux (Nomenclature) -->
    <h4 class="mb-3 mt-4 fw-semibold text-primary"><i class="bi bi-tags-fill me-2"></i>Nomenclature et Catégorisation</h4>
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-file-earmark-text fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Types de Décision</h5>
                    <p class="small text-muted mb-4">Règlements, directives, recommandations, etc.</p>
                    <a href="{{ route('decision-types.index') }}" class="btn btn-outline-primary btn-sm w-100">Gérer les types</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-collection fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Catégories</h5>
                    <p class="small text-muted mb-4">Classification thématique globale.</p>
                    <a href="{{ route('decision-categories.index') }}" class="btn btn-outline-primary btn-sm w-100">Gérer les catégories</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-diagram-3 fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Domaines d'Action</h5>
                    <p class="small text-muted mb-4">Découpage en piliers stratégiques de la CEEAC.</p>
                    <a href="{{ route('domains.index') }}" class="btn btn-outline-primary btn-sm w-100">Gérer les domaines</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-calendar-event fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Sessions</h5>
                    <p class="small text-muted mb-4">Sessions ordinaires et extraordinaires des instances.</p>
                    <a href="{{ route('sessions.index') }}" class="btn btn-outline-primary btn-sm w-100">Gérer les sessions</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Organisation Institutionnelle -->
    <h4 class="mb-3 mt-4 fw-semibold text-primary"><i class="bi bi-building me-2"></i>Organisation Institutionnelle</h4>
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-globe-americas fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">États Membres</h6>
                        <a href="{{ route('countries.index') }}" class="text-decoration-none small fw-medium">Gérer les pays &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-bank fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Institutions et Organes</h6>
                        <a href="{{ route('institutions.index') }}" class="text-decoration-none small fw-medium">Gérer les organes &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-diagram-2 fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Départements internes</h6>
                        <a href="{{ route('departments.index') }}" class="text-decoration-none small fw-medium">Gérer l'organigramme &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des Accès et Utilisateurs -->
    <h4 class="mb-3 mt-4 fw-semibold text-primary"><i class="bi bi-people-fill me-2"></i>Gestion des Accès</h4>
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-badge fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Comptes Utilisateurs</h6>
                        <a href="{{ route('users.index') }}" class="text-decoration-none small fw-medium text-warning">Gérer les utilisateurs &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 50px; height: 50px;">
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Rôles et Habilitations</h6>
                        <a href="{{ route('roles.index') }}" class="text-decoration-none small fw-medium text-warning">Gérer les rôles &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Traçabilité -->
    <h4 class="mb-3 mt-4 fw-semibold text-primary"><i class="bi bi-shield-check me-2"></i>Sécurité et Traçabilité</h4>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1"><i class="bi bi-journal-medical text-danger me-2"></i>Journal d'Audit</h5>
                            <p class="small text-muted mb-0">Historique inaltérable de toutes les actions clés du système (création, validation, suppression).</p>
                        </div>
                        <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-danger btn-sm">Consulter le journal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
