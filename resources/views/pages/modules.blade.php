@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white pt-5 pb-5" style="background: linear-gradient(135deg, var(--ceeac-blue) 0%, #004085 100%);">
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">Accueil</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Modules</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold mb-3">Les Modules SUIVI-DEC</h1>
                <p class="lead opacity-75 mb-0">Une architecture logicielle modulaire pensée pour le pilotage exhaustif des programmes communautaires.</p>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<section class="section-padding bg-white">
    <div class="container">
        
        <!-- Module 1 -->
        <div class="row align-items-center mb-5 pb-5 border-bottom">
            <div class="col-md-5 order-md-2 mb-4 mb-md-0">
                <div class="bg-light p-5 rounded-4 text-center">
                    <i class="bi bi-journal-text text-primary" style="font-size: 5rem;"></i>
                </div>
            </div>
            <div class="col-md-7 order-md-1 pe-md-5">
                <span class="badge bg-primary-subtle text-primary mb-2">Module Principal</span>
                <h3 class="fw-bold mb-3">Gestion des Décisions</h3>
                <p class="text-muted mb-4">
                    Le module cœur de l'application permet la numérisation complète de l'arsenal juridique et stratégique de la CEEAC. Chaque décision y est cataloguée avec ses métadonnées critiques :
                </p>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Date d'adoption et de clôture</li>
                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Instance émettrice (Conférence, Conseil, etc.)</li>
                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Catégorie (Paix, Sécurité, Environnement)</li>
                    <li><i class="bi bi-check2 text-success me-2"></i> Assignation des Directeurs ou points focaux responsables</li>
                </ul>
            </div>
        </div>

        <!-- Module 2 -->
        <div class="row align-items-center mb-5 pb-5 border-bottom">
            <div class="col-md-5 mb-4 mb-md-0">
                <div class="bg-light p-5 rounded-4 text-center">
                    <i class="bi bi-diagram-3 text-primary" style="font-size: 5rem;"></i>
                </div>
            </div>
            <div class="col-md-7 ps-md-5">
                <span class="badge bg-primary-subtle text-primary mb-2">Planification</span>
                <h3 class="fw-bold mb-3">Construction des Plans d'Action</h3>
                <p class="text-muted mb-4">
                    Une décision sans plan métier est vouée à l'inexécution. Ce module permet de découper la résolution macroscopique en éléments concrets, structurés de manière arborescente.
                </p>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="border p-3 rounded">
                            <h6 class="fw-bold"><i class="bi bi-arrow-right-short text-primary"></i> Axes Stratégiques</h6>
                            <p class="small text-muted mb-0">Regrouper les efforts par thématique</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border p-3 rounded">
                            <h6 class="fw-bold"><i class="bi bi-arrow-right-short text-primary"></i> Actions Métiers</h6>
                            <p class="small text-muted mb-0">Objectifs intermédiaires concrets</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border p-3 rounded">
                            <h6 class="fw-bold"><i class="bi bi-arrow-right-short text-primary"></i> Activités</h6>
                            <p class="small text-muted mb-0">Tâches planifiées assignées aux agents</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border p-3 rounded">
                            <h6 class="fw-bold"><i class="bi bi-arrow-right-short text-primary"></i> Livrables & Jalons</h6>
                            <p class="small text-muted mb-0">Preuves physiques ou échéances temporelles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Module 3 -->
        <div class="row align-items-center mb-5 pb-5">
            <div class="col-md-5 order-md-2 mb-4 mb-md-0">
                <div class="bg-light p-5 rounded-4 text-center">
                    <i class="bi bi-clipboard-data text-primary" style="font-size: 5rem;"></i>
                </div>
            </div>
            <div class="col-md-7 order-md-1 pe-md-5">
                <span class="badge bg-primary-subtle text-primary mb-2">Suivi & Évaluation</span>
                <h3 class="fw-bold mb-3">Reporting et Workflows Validatifs</h3>
                <p class="text-muted mb-0">
                    Les responsables soumettent des rapports d'avancement réguliers. Ces soumissions génèrent des notifications et entrent dans un pipeline d'approbation à N-niveaux. Les valideurs peuvent approuver ou rejeter le rapport. C'est uniquement après validation officielle que le taux de progression global d'une décision augmente, garantissant l'intégrité des statistiques institutionnelles.
                </p>
            </div>
        </div>

    </div>
</section>

@endsection
