@extends('layouts.public')

@section('content')
<section class="bg-primary text-white pt-5 pb-5" style="background: linear-gradient(135deg, var(--ceeac-blue) 0%, #004085 100%);">
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">Accueil</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Instances</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold mb-3">Les Instances de la CEEAC</h1>
                <p class="lead opacity-75 mb-0">Organes habilités à prendre les décisions suivies par la plateforme institutionnelle.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-light">
    <div class="container">
        <div class="row g-4 justify-content-center">
            
            <div class="col-lg-10">
                <!-- Instance 1 -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-3 bg-primary d-flex align-items-center justify-content-center text-white flex-column py-4" style="background-color: var(--ceeac-blue) !important;">
                            <i class="bi bi-star-fill fs-1 mb-2"></i>
                            <span class="fw-bold text-uppercase small text-center px-2">Organe Suprême</span>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-dark">Conférence des Chefs d'État et de Gouvernement</h4>
                                <p class="text-muted mb-0">L'organe suprême de la CEEAC. Elle définit la politique générale et les grandes orientations de la Communauté. Les décisions adoptées à ce niveau engagent l'ensemble des 11 États membres et constituent la base juridique prioritaire suivie dans SUIVI-DEC.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instance 2 -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-3 bg-light d-flex align-items-center justify-content-center text-primary flex-column py-4 border-end">
                            <i class="bi bi-briefcase-fill fs-1 mb-2"></i>
                            <span class="fw-bold text-uppercase small text-center px-2">Exécutif Stratégique</span>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-dark">Le Conseil des Ministres</h4>
                                <p class="text-muted mb-0">Composé des ministres en charge de l'intégration régionale ou des ministres sectoriels selon l'ordre du jour. Le Conseil assure le fonctionnement et le développement de la Communauté. Il adopte les Règlements qui s'imposent aux États membres.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instance 3 -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-3 bg-light d-flex align-items-center justify-content-center text-primary flex-column py-4 border-end">
                            <i class="bi bi-people-fill fs-1 mb-2"></i>
                            <span class="fw-bold text-uppercase small text-center px-2">Préparation & Suivi</span>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-dark">Le Comité des Représentants Permanents (COREP)</h4>
                                <p class="text-muted mb-0">Constitué des Ambassadeurs accrédités auprès de la CEEAC. Il prépare les travaux du Conseil des Ministres et suit l’exécution des politiques et décisions adoptées par les instances supérieures.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instance 4 -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-3 bg-light d-flex align-items-center justify-content-center text-primary flex-column py-4 border-end">
                            <i class="bi bi-tools fs-1 mb-2"></i>
                            <span class="fw-bold text-uppercase small text-center px-2">Appui Technique</span>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-dark">Les Comités Techniques Spécialisés (CTS)</h4>
                                <p class="text-muted mb-0">Composés des experts des États membres de différents secteurs d'activité (défense, agriculture, santé, infrastructures). Ils formulent des recommandations techniques sectorielles qui seront ensuite entérinées par le Conseil ou la Conférence.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instance 5 -->
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-3 d-flex align-items-center justify-content-center text-white flex-column py-4" style="background-color: var(--ceeac-accent);">
                            <i class="bi bi-building fs-1 mb-2"></i>
                            <span class="fw-bold text-uppercase small text-center px-2">Ordonnateur Exécutif</span>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body p-4">
                                <h4 class="fw-bold text-dark">La Commission de la CEEAC</h4>
                                <p class="text-muted mb-0">L'organe exécutif central de la Communauté. Dirigée par un Président assisté de Commissaires, elle est garante du traité, représente la CEEAC et est responsable devant les autres instances de la mise en œuvre globale de l'ensemble des décisions suivies dans ce système.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
