@extends('layouts.public')

@section('content')
<section class="bg-primary text-white pt-5 pb-5" style="background: linear-gradient(135deg, var(--ceeac-blue) 0%, #004085 100%);">
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">Accueil</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Rapports</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold mb-3">Reporting Stratégique</h1>
                <p class="lead opacity-75 mb-0">Génération et consultation des rapports d'état sur l'avancement des dossiers communautaires.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-white">
    <div class="container">
        
        <div class="row align-items-center justify-content-between mb-5">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <h3 class="fw-bold text-dark mb-4">La finalité de SUIVI-DEC : La Reddition des Comptes</h3>
                <p class="text-muted mb-4">
                    Le recueil de données au sein de la plateforme SUIVI-DEC n'a de sens que s'il est restitué aux décideurs sous une forme synthétique et exploitable. 
                    Le module de reporting intégré permet de générer des états PDF et des tableaux de bord interactifs à destination de :
                </p>
                <ul class="list-group list-group-flush mb-4 shadow-sm border rounded">
                    <li class="list-group-item bg-light text-primary fw-medium"><i class="bi bi-chevron-right me-2"></i> La Présidence de la Commission</li>
                    <li class="list-group-item bg-light text-primary fw-medium"><i class="bi bi-chevron-right me-2"></i> Le Conseil des Ministres</li>
                    <li class="list-group-item bg-light text-primary fw-medium"><i class="bi bi-chevron-right me-2"></i> La Conférence des Chefs d'État</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <!-- Illustration d'un rapport PDF ou Graphique -->
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="card h-100 border-0 shadow-sm text-center p-4">
                            <i class="bi bi-file-earmark-pdf text-danger fs-1 mb-3"></i>
                            <h5 class="fw-bold">Fiche Synthétique PDF</h5>
                            <p class="small text-muted mb-0">Rapport formaté exportant l'historique complet, les validations et les indicateurs d'une décision spécifique pour impression institutionnelle.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card h-100 border-0 shadow-sm text-center p-4">
                            <i class="bi bi-file-earmark-excel text-success fs-1 mb-3"></i>
                            <h5 class="fw-bold">Extrait Excel (.xlsx)</h5>
                            <p class="small text-muted mb-0">Extraction matricielle des portefeuilles de décisions selon de multiples filtres (statut, priorité, instance) pour traitement par les experts du COREP.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-primary bg-opacity-10 rounded-4 p-5 text-center mt-5">
            <h4 class="fw-bold text-primary mb-3">Exemples de rapports générés par le système</h4>
            <p class="text-muted mb-4 mx-auto" style="max-width: 600px;">
                Certains rapports sont accessibles publiquement pour les citoyens de l'espace CEEAC, tandis que les rapports d'état confidentiels nécessitent une habilitation.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="#" class="btn btn-outline-primary bg-white px-4 py-2"><i class="bi bi-lock-fill me-2"></i> Bilan Annuel d'Exécution</a>
                <a href="#" class="btn btn-outline-primary bg-white px-4 py-2"><i class="bi bi-lock-fill me-2"></i> État des Activités - Q3 2026</a>
            </div>
            <p class="small text-muted mt-3 mb-0"><i class="bi bi-info-circle me-1"></i> La consultation de ces documents nécessite une connexion valide à l'intranet SUIVI-DEC.</p>
        </div>

    </div>
</section>
@endsection
