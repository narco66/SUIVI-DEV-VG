@extends('layouts.public')

@section('content')

<!-- 2. HERO SECTION -->
<section class="hero-section text-center text-md-start">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-7 mb-5 mb-lg-0">
                <span class="badge bg-white text-primary mb-3 px-3 py-2 rounded-pill fw-medium" style="letter-spacing: 1px;">PORTAIL OFFICIEL</span>
                <h1 class="display-4 fw-bold mb-4" style="line-height: 1.2;">Suivi stratégique des décisions de la CEEAC</h1>
                <p class="lead mb-5 opacity-75" style="max-width: 600px;">
                    La plateforme SUIVI-DEC permet de suivre en temps réel la mise en œuvre des décisions, recommandations et actes adoptés par les instances de la Communauté Économique des États de l'Afrique Centrale.
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('login') }}" class="btn btn-light text-primary btn-lg px-4 fw-bold rounded-pill">
                        Accéder à la plateforme <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="#presentation" class="btn btn-outline-light btn-lg px-4 fw-medium rounded-pill">
                        En savoir plus
                    </a>
                </div>
            </div>
            <div class="col-12 col-lg-5 d-none d-lg-block">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1541359927273-d76820fc43f9?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Réunion institutionnelle" class="img-fluid rounded-4 shadow-lg" style="border: 5px solid rgba(255,255,255,0.1);">
                    <!-- Badge flottant -->
                    <div class="position-absolute bg-white text-dark p-3 rounded-3 shadow-sm d-flex align-items-center gap-3" style="bottom: -20px; left: -30px;">
                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-circle">
                            <i class="bi bi-graph-up-arrow fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-0 small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Taux d'exécution global</p>
                            <h4 class="mb-0 fw-bold">78.5% <span class="text-success fs-6"><i class="bi bi-caret-up-fill"></i>+2%</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. PRESENTATION DE LA PLATEFORME -->
<section id="presentation" class="section-padding bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                <h2 class="section-title">Pourquoi SUIVI-DEC ?</h2>
                <div class="bg-primary mb-4" style="width: 60px; height: 4px; border-radius: 2px;"></div>
                <p class="text-muted fs-5 mb-4">
                    Face à la croissance du volume et de la complexité des résolutions communautaires, la Commission de la CEEAC se dote d'un outil numérique de pointe pour garantir l'efficacité de son action.
                </p>
                <ul class="list-unstyled mb-4">
                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-success fs-5 me-3 mt-1"></i>
                        <div>
                            <strong class="text-dark">Suivi rigoureux</strong>
                            <p class="text-muted small mb-0">Assurer une exécution effective des décisions dans les délais impartis par les instances.</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-success fs-5 me-3 mt-1"></i>
                        <div>
                            <strong class="text-dark">Transparence institutionnelle</strong>
                            <p class="text-muted small mb-0">Offrir une visibilité complète aux États membres sur l'avancement des dossiers communautaires.</p>
                        </div>
                    </li>
                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill text-success fs-5 me-3 mt-1"></i>
                        <div>
                            <strong class="text-dark">Pilotage stratégique</strong>
                            <p class="text-muted small mb-0">Faciliter la prise de décision par la Commission grâce à des données fiables et des tableaux de bord analytiques.</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-light p-4 rounded-4 text-center h-100 d-flex flex-column justify-content-center border" style="border-color: rgba(0,0,0,0.05) !important;">
                            <h2 class="display-5 fw-bold text-primary mb-1">200+</h2>
                            <p class="text-muted small text-uppercase mb-0 fw-medium">Décisions Actives</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-4 rounded-4 text-center h-100 d-flex flex-column justify-content-center border mt-4" style="border-color: rgba(0,0,0,0.05) !important;">
                            <h2 class="display-5 fw-bold text-primary mb-1">11</h2>
                            <p class="text-muted small text-uppercase mb-0 fw-medium">États Membres</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. MODULES PRINCIPAUX -->
<section class="section-padding border-top" style="background-color: #f8fcfd;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Les Modules de la Plateforme</h2>
            <p class="section-subtitle mx-auto" style="max-width: 700px;">Un écosystème logiciel complet pour administrer, suivre et évaluer l'action de la CEEAC de bout en bout.</p>
        </div>

        <div class="row g-4">
            <!-- Module 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-module p-3">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <h5 class="fw-bold fs-6">Gestion des Décisions</h5>
                        <p class="text-muted small mb-0">Centralisation, classification et assignation des actes adoptés par les instances de la CEEAC.</p>
                    </div>
                </div>
            </div>
            <!-- Module 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-module p-3">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h5 class="fw-bold fs-6">Plans d'Action</h5>
                        <p class="text-muted small mb-0">Déclinaison des décisions en axes stratégiques, actions concrètes et activités mesurables.</p>
                    </div>
                </div>
            </div>
            <!-- Module 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-module p-3">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="bi bi-kanban"></i>
                        </div>
                        <h5 class="fw-bold fs-6">Suivi des Activités</h5>
                        <p class="text-muted small mb-0">Monitoring au niveau granulaire des tâches incombant aux différents départements.</p>
                    </div>
                </div>
            </div>
            <!-- Module 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-module p-3">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <h5 class="fw-bold fs-6">Dashboard Analytique</h5>
                        <p class="text-muted small mb-0">Visualisation des KPIs, taux d'exécution et calcul de performances de mise en œuvre.</p>
                    </div>
                </div>
            </div>
            <!-- Module 5 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-module p-3">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5 class="fw-bold fs-6">Gestion des Livrables</h5>
                        <p class="text-muted small mb-0">Traçabilité des jalons et réception officielle des rapports ou documents exigés.</p>
                    </div>
                </div>
            </div>
            <!-- Module 6 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-module p-3">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <h5 class="fw-bold fs-6">Gestion Documentaire</h5>
                        <p class="text-muted small mb-0">GED intégrée pour archiver de façon sécurisée les actes juridiques et preuves d'exécution.</p>
                    </div>
                </div>
            </div>
            <!-- Module 7 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-module p-3">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5 class="fw-bold fs-6">Suivi des Indicateurs</h5>
                        <p class="text-muted small mb-0">Mesure d'impact des décisions à travers des indicateurs de résultat clairs.</p>
                    </div>
                </div>
            </div>
            <!-- Module 8 -->
            <div class="col-md-6 col-lg-3">
                <div class="card card-module p-3">
                    <div class="card-body">
                        <div class="icon-box">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                        </div>
                        <h5 class="fw-bold fs-6">Reporting Stratégique</h5>
                        <p class="text-muted small mb-0">Génération aux formats PDF/Excel de rapports d'état à l'intention des Chefs d'État et des ministres.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. PROCESSUS DE SUIVI & 8. AVANTAGES -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="row g-5">
            <!-- Workflow -->
            <div class="col-lg-12 mb-5">
                <div class="text-center mb-5">
                    <h2 class="section-title">Workflow Institutionnel</h2>
                    <p class="section-subtitle mx-auto">Le cycle de vie d'une décision communautaire dans SUIVI-DEC.</p>
                </div>
                
                <div class="d-none d-md-flex justify-content-between align-items-center position-relative px-4">
                    <!-- Barre de connexion -->
                    <div class="position-absolute bg-primary bg-opacity-25" style="height: 3px; top: 40px; left: 10%; right: 10%; z-index: 1;"></div>
                    
                    @php $workflow = [
                        ['icon' => 'bank', 'title' => 'Adoption', 'desc' => 'Par les instances'],
                        ['icon' => 'diagram-2', 'title' => 'Planification', 'desc' => 'Axes et actions'],
                        ['icon' => 'person-check', 'title' => 'Assignation', 'desc' => 'Aux acteurs'],
                        ['icon' => 'arrow-repeat', 'title' => 'Exécution', 'desc' => 'Mises à jour manuelles'],
                        ['icon' => 'check-all', 'title' => 'Validation', 'desc' => 'Approbations internes'],
                        ['icon' => 'pie-chart', 'title' => 'Rapports', 'desc' => 'Consolidation finale']
                    ] @endphp
                    
                    @foreach($workflow as $step)
                    <div class="text-center position-relative" style="z-index: 2; width: 140px;">
                        <div class="bg-white border border-2 border-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; box-shadow: 0 0 0 5px white;">
                            <i class="bi bi-{{ $step['icon'] }} fs-3 text-primary"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 fs-6">{{ $step['title'] }}</h6>
                        <small class="text-muted">{{ $step['desc'] }}</small>
                    </div>
                    @endforeach
                </div>
                
                <!-- Version Mobile Workflow -->
                <div class="d-md-none">
                    @foreach($workflow as $index => $step)
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-{{ $step['icon'] }} fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $index + 1 }}. {{ $step['title'] }}</h6>
                            <p class="text-muted small mb-0">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>            
        </div>
    </div>
</section>

<!-- 6. INSTANCES & DASHBOARD (MOCKUP) -->
<section class="section-padding" style="background-color: var(--ceeac-blue); color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0">
                <h6 class="text-warning fw-bold text-uppercase tracking-wide mb-2">Sources des engagements</h6>
                <h2 class="display-6 fw-bold mb-4">Instances de la CEEAC</h2>
                <p class="opacity-75 mb-4">La plateforme assure le monitoring des décisions issues des organes majeurs de la CEEAC afin de garantir leur application effective par la Commission et les États membres.</p>
                
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 d-flex"><i class="bi bi-bookmark-fill text-warning me-3 mt-1"></i> <span>La Conférence des Chefs d'État et de Gouvernement</span></li>
                    <li class="mb-3 d-flex"><i class="bi bi-bookmark-fill text-warning me-3 mt-1"></i> <span>Le Conseil des Ministres</span></li>
                    <li class="mb-3 d-flex"><i class="bi bi-bookmark-fill text-warning me-3 mt-1"></i> <span>Le COREP (Comité des Représentants Permanents)</span></li>
                    <li class="mb-3 d-flex"><i class="bi bi-bookmark-fill text-warning me-3 mt-1"></i> <span>Les Comités Techniques Spécialisés (CTS)</span></li>
                    <li class="d-flex"><i class="bi bi-bookmark-fill text-warning me-3 mt-1"></i> <span>La Commission de la CEEAC</span></li>
                </ul>
            </div>
            <div class="col-lg-7">
                <div class="bg-white rounded-3 p-2 shadow-lg" style="transform: perspective(1000px) rotateY(-5deg) rotateX(2deg);">
                    <div class="bg-light rounded p-4 text-dark">
                        <!-- Pseudo Dashboard -->
                        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                            <h5 class="fw-bold mb-0">Aperçu Global</h5>
                            <span class="badge bg-primary">Temps Réel</span>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="text-primary fw-bold mb-0">145</h3>
                                    <small class="text-muted text-uppercase" style="font-size:0.65rem;">Décisions suivies</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="text-success fw-bold mb-0">340</h3>
                                    <small class="text-muted text-uppercase" style="font-size:0.65rem;">Actions en cours</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="text-warning fw-bold mb-0">62%</h3>
                                    <small class="text-muted text-uppercase" style="font-size:0.65rem;">Taux moy. d'exécution</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="text-info fw-bold mb-0">89</h3>
                                    <small class="text-muted text-uppercase" style="font-size:0.65rem;">Livrables validés</small>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="fw-medium small mb-2">Avancement par domaine</p>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small text-muted mb-1"><span>Paix et Sécurité</span><span>85%</span></div>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-success" style="width: 85%"></div></div>
                            </div>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small text-muted mb-1"><span>Intégration Physique</span><span>45%</span></div>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-warning" style="width: 45%"></div></div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-between small text-muted mb-1"><span>Agriculture et Environnement</span><span>60%</span></div>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-primary" style="width: 60%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 10. PARTENAIRES -->
<section class="section-padding bg-light border-top">
    <div class="container text-center">
        <h6 class="text-muted text-uppercase fw-bold mb-4" style="letter-spacing: 1px;">Au service de l'intégration régionale</h6>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 opacity-50">
            <!-- Simulated Partners names since we don't have images -->
            <span class="fs-5 fw-bold font-monospace">ANGOLA</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">BURUNDI</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">CAMEROUN</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">RCA</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">CONGO</span><br class="d-md-none">
            <span class="fs-5 fw-bold font-monospace px-3 text-muted d-none d-md-inline">|</span>
            <span class="fs-5 fw-bold font-monospace">RDC</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">GABON</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">GUINÉE ÉQ.</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">RWANDA</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">SAO TOMÉ</span>
            <span class="fs-5 fw-bold font-monospace px-3 text-muted">|</span>
            <span class="fs-5 fw-bold font-monospace">TCHAD</span>
        </div>
    </div>
</section>

@endsection
