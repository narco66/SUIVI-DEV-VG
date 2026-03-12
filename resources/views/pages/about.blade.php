@extends('layouts.public')

@section('content')
<section class="bg-primary text-white pt-5 pb-5" style="background: linear-gradient(135deg, var(--ceeac-blue) 0%, #004085 100%);">
    <div class="container pt-4 pb-4">
        <div class="row">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white opacity-75 text-decoration-none">Accueil</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">À propos</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold mb-3">La Commission de la CEEAC</h1>
                <p class="lead opacity-75 mb-0">Communauté Économique des États de l'Afrique Centrale.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding bg-white">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <!-- Emblème de la CEEAC PlaceHolder -->
                <div class="bg-light rounded-4 p-5 text-center border d-flex flex-column align-items-center justify-content-center h-100" style="min-height: 400px; border-color: rgba(0,0,0,0.05) !important;">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-4 shadow" style="width: 150px; height: 150px; color: white;">
                        <h1 class="mb-0 fw-bold">CEEAC</h1>
                    </div>
                    <h4 class="fw-bold text-dark">Vers une intégration sous-régionale renforcée</h4>
                </div>
            </div>
            
            <div class="col-lg-6">
                <p class="text-uppercase text-primary fw-bold tracking-wide small mb-2">Notice Institutionnelle</p>
                <h2 class="fw-bold text-dark mb-4">Notre Mission</h2>
                <p class="text-muted text-justify">
                    La Communauté Économique des États de l'Afrique Centrale a pour mission première de promouvoir et de renforcer une coopération harmonieuse et un développement dynamique, autonome et auto-entretenu dans tous les domaines des activités économiques et culturelles dans l'optique d'instaurer à terme un marché commun.
                </p>
                
                <h4 class="fw-bold text-dark mt-5 mb-3">La Réforme Institutionnelle</h4>
                <p class="text-muted text-justify mb-4">
                    Suite à la réforme de 2019 entérinée par la Conférence des Chefs d'État, l'ancien "Secrétariat Général" a été érigé en "Commission". Cette mutation dote l'organisation d'un exécutif plus fort, capable d'initier et de mener à bien de vastes chantiers d'intégration régionale avec un mandat clair.
                </p>

                <h4 class="fw-bold text-dark mb-3">Plateforme SUIVI-DEC</h4>
                <p class="text-muted text-justify">
                    La création de cet outil numérique s'inscrit dans la démarche de modernisation et de digitalisation des process de la CEEAC voulue par la présidence de la Commission. L'application SUIVI-DEC a été conceptualisée et développée par les équipes internes pour répondre au besoin vital de traçabilité des engagements pris par la Communauté envers ses citoyens.
                </p>
            </div>
        </div>
        
        <div class="row mt-5 pt-5 border-top text-center">
            <div class="col-12">
                <h5 class="fw-bold text-muted mb-4">États Membres</h5>
                <p class="text-muted">
                    République d'Angola • République du Burundi • République du Cameroun • République Centrafricaine • 
                    République du Congo • République Démocratique du Congo • République Gabonaise • 
                    République de Guinée Équatoriale • République du Rwanda • République Démocratique de Sao Tomé-et-Principe • République du Tchad
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
