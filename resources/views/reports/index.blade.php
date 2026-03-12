@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Rapports Stratégiques</h2>
            <p class="text-muted mb-0">Génération de rapports exécutifs pour les instances dirigeantes</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <a href="{{ route('reports.strategic.pdf') }}" class="btn btn-danger px-4 rounded-pill shadow-sm" target="_blank">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i> Générer le Rapport PDF
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Aperçu Macro -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-dark mb-4 pb-3 border-bottom d-flex align-items-center">
                        <i class="bi bi-bar-chart-fill text-primary me-2"></i> Aperçu Macro-Stratégique
                    </h5>
                    
                    <div class="row g-4 text-center">
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded-4 border">
                                <h3 class="display-5 fw-bold text-primary mb-0">{{ $totalDecisions }}</h3>
                                <p class="text-muted text-uppercase small fw-medium mt-2 mb-0">Décisions Suivies</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded-4 border">
                                <h3 class="display-5 fw-bold text-success mb-0">{{ number_format($averageProgress, 1) }}%</h3>
                                <p class="text-muted text-uppercase small fw-medium mt-2 mb-0">Taux d'Avancement Moyen</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded-4 border">
                                <h3 class="display-5 fw-bold text-warning mb-0">{{ $delayedDecisions }}</h3>
                                <p class="text-muted text-uppercase small fw-medium mt-2 mb-0">En Souffrance / Retard</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-4 rounded-4 border">
                                <h3 class="display-5 fw-bold text-info mb-0">{{ $completedDecisions }}</h3>
                                <p class="text-muted text-uppercase small fw-medium mt-2 mb-0">Décisions Achevées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations sur le Rapport -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-primary text-white overflow-hidden position-relative">
                <div class="position-absolute end-0 top-0 opacity-10 p-4" style="font-size: 8rem; line-height: 1;">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div class="card-body p-4 p-md-5 position-relative z-1">
                    <h5 class="fw-bold mb-4">Contenu du Rapport</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-info me-3 mt-1 fs-5"></i>
                            <span>Synthèse exécutive de la performance globale de l'institution.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-info me-3 mt-1 fs-5"></i>
                            <span>Répartition détaillée de l'avancement par domaine d'intervention.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-info me-3 mt-1 fs-5"></i>
                            <span>Mise en exergue des décisions prioritaires et des blocages éventuels nécessitant un arbitrage politique.</span>
                        </li>
                    </ul>
                    <div class="mt-5 pt-4 border-top border-white border-opacity-25 text-center">
                        <p class="small text-white-50 mb-0">Date de génération: {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
