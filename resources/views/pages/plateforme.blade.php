@extends('layouts.public')

@section('content')
<!-- Hero Section for Internal Pages -->
<section class="bg-primary text-white pt-5 pb-5" style="background: linear-gradient(135deg, var(--ceeac-blue) 0%, #004085 100%);">
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">Accueil</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">La Plateforme</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold mb-3">La Plateforme SUIVI-DEC</h1>
                <p class="lead opacity-75 mb-0">Comprendre l'écosystème numérique de suivi des décisions communautaires de la CEEAC.</p>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 mb-4 mb-lg-0 pe-lg-5">
                <h3 class="fw-bold text-dark mb-4">Genèse et Raison d'être</h3>
                <p class="text-muted text-justify">
                    La Communauté Économique des États de l'Afrique Centrale (CEEAC) adopte chaque année des dizaines de décisions majeures lors de la Conférence des Chefs d'État et de Gouvernement, déclinées en règlements et plans d'action par le Conseil des Ministres.
                </p>
                <p class="text-muted text-justify">
                    Face au défi que représente le suivi effectif de la mise en œuvre de ces directives au sein des 11 États membres et par les départements de la Commission, la nécessité d'un outil centralisé s'est imposée. SUIVI-DEC répond à cet impératif d'efficacité architecturale.
                </p>
                <div class="bg-light p-4 rounded mt-4 border-start border-4 border-primary">
                    <p class="mb-0 fst-italic text-dark">
                        "L'intégration régionale ne se décrète pas, elle s'exécute. La plateforme SUIVI-DEC est la matérialisation de cette volonté d'exécution."
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Réunion stratégique" class="img-fluid rounded shadow">
            </div>
        </div>

        <div class="row g-5 mt-4">
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Sécurité et Souveraineté</h5>
                    <p class="text-muted small">Les données institutionnelles sont chiffrées et la souveraineté numérique des États membres est garantie à travers des protocoles stricts de gestion des accès.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-clock-history fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Données en Temps Réel</h5>
                    <p class="text-muted small">Les tableaux de bord sont alimentés instantanément lors de chaque mise à jour de terrain, offrant aux présidences une vision à la minute de l'état d'avancement.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-diagram-3 fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Interopérabilité</h5>
                    <p class="text-muted small">Architecture pensée pour se connecter facilement avec les systèmes de Gestion Électronique de Documents (GED) existants de la Commission.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5" style="background-color: var(--ceeac-gray-light);">
    <div class="container text-center">
        <h3 class="fw-bold mb-3">Prêt à explorer les capacités du système ?</h3>
        <p class="text-muted mb-4">Découvrez en détail les modules qui composent la plateforme SUIVI-DEC.</p>
        <a href="{{ url('/modules') }}" class="btn btn-primary px-4 py-2 rounded-pill">Découvrir les Modules <i class="bi bi-arrow-right ms-2"></i></a>
    </div>
</section>
@endsection
