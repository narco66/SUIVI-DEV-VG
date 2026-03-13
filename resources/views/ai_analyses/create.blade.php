@extends('layouts.app')

@section('title', 'Assistant IA — Analyse de document institutionnel')

@section('content')
<div class="container-fluid p-0">

    {{-- En-tête --}}
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="bi bi-robot text-primary me-3 fs-2"></i> Assistant IA d'Analyse
            </h2>
            <p class="text-muted mb-0">Transformez vos actes institutionnels en éléments opérationnels de suivi.</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <a href="{{ route('home') }}" class="btn btn-light px-4 rounded-pill shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Tableau de bord
            </a>
        </div>
    </div>

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Formulaire principal --}}
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 position-relative overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('ai-analyses.analyze') }}" method="POST" id="ai-form" novalidate>
                        @csrf

                        <h5 class="fw-bold text-dark mb-4 pb-3 border-bottom d-flex align-items-center">
                            <i class="bi bi-file-earmark-text text-primary me-2"></i> Document à analyser
                        </h5>

                        {{-- Erreurs de validation --}}
                        @if($errors->any())
                            <div class="alert alert-danger rounded-4 mb-4">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3 mb-4">
                            {{-- Type d'acte --}}
                            <div class="col-md-6">
                                <label for="document_type" class="form-label fw-semibold text-muted small text-uppercase">
                                    Type d'acte <span class="text-danger">*</span>
                                </label>
                                <select class="form-select border-0 bg-light py-2 @error('document_type') is-invalid @enderror"
                                        id="document_type" name="document_type" required>
                                    <option value="" disabled @if(!old('document_type')) selected @endif>Sélectionner...</option>
                                    <option value="décision"       @selected(old('document_type') === 'décision')>Décision</option>
                                    <option value="résolution"     @selected(old('document_type') === 'résolution')>Résolution</option>
                                    <option value="règlement"      @selected(old('document_type') === 'règlement')>Règlement</option>
                                    <option value="directive"      @selected(old('document_type') === 'directive')>Directive</option>
                                    <option value="communiqué"     @selected(old('document_type') === 'communiqué')>Communiqué final</option>
                                    <option value="recommandation" @selected(old('document_type') === 'recommandation')>Recommandation</option>
                                    <option value="rapport"        @selected(old('document_type') === 'rapport')>Rapport</option>
                                    <option value="compte-rendu"   @selected(old('document_type') === 'compte-rendu')>Compte rendu</option>
                                    <option value="autre"          @selected(old('document_type') === 'autre')>Autre document</option>
                                </select>
                                @error('document_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Référence officielle --}}
                            <div class="col-md-6">
                                <label for="document_reference" class="form-label fw-semibold text-muted small text-uppercase">
                                    Référence officielle
                                </label>
                                <input type="text"
                                       class="form-control border-0 bg-light py-2 @error('document_reference') is-invalid @enderror"
                                       id="document_reference" name="document_reference"
                                       value="{{ old('document_reference') }}"
                                       placeholder="Ex : Décision N° 12/CEEAC/CCEG/23">
                                @error('document_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Titre --}}
                        <div class="mb-4">
                            <label for="document_title" class="form-label fw-semibold text-muted small text-uppercase">
                                Titre global <span class="text-muted fw-normal">(optionnel)</span>
                            </label>
                            <input type="text"
                                   class="form-control border-0 bg-light py-2 @error('document_title') is-invalid @enderror"
                                   id="document_title" name="document_title"
                                   value="{{ old('document_title') }}"
                                   placeholder="Ex : Création du fonds de solidarité climatique">
                            <div class="form-text">Si laissé vide, l'IA tentera de déduire le titre à partir du contenu.</div>
                            @error('document_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Texte source --}}
                        <div class="mb-5">
                            <label for="source_text" class="form-label fw-semibold text-muted small text-uppercase">
                                Texte intégral du document <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control border-0 bg-light p-3 @error('source_text') is-invalid @enderror"
                                      id="source_text" name="source_text" rows="12"
                                      minlength="50" maxlength="80000"
                                      placeholder="Collez ici l'intégralité du texte institutionnel. Plus le texte est complet, plus le découpage sera précis.">{{ old('source_text') }}</textarea>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div class="form-text">Minimum 50 caractères — Maximum 80 000 caractères.</div>
                                <span id="char-counter" class="small text-muted">0 / 80 000</span>
                            </div>
                            @error('source_text')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm fw-bold"
                                    id="submit-btn">
                                <i class="bi bi-stars me-2"></i> Lancer l'analyse intelligente
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Overlay de chargement --}}
                <div id="loader-overlay"
                     class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-90 rounded-4 d-none flex-column align-items-center justify-content-center"
                     style="z-index:10;">
                    <div class="spinner-border text-primary border-4 mb-3" role="status" style="width:3rem;height:3rem;"></div>
                    <h5 class="fw-bold text-dark">L'IA analyse le document...</h5>
                    <p class="text-muted mb-0 text-center px-4">
                        Cette opération peut prendre jusqu'à une minute selon la longueur du texte.<br>
                        <small>Ne fermez pas cet onglet.</small>
                    </p>
                </div>
            </div>
        </div>

        {{-- Panneau d'aide --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold mb-4">Comment ça fonctionne ?</h5>
                    <p class="text-muted small mb-4">
                        L'assistant IA lit votre acte institutionnel et le décompose automatiquement en arborescence de suivi-évaluation.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-4 d-flex align-items-start">
                            <div class="bg-white rounded-circle shadow-sm me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:40px;height:40px;">
                                <i class="bi bi-1-circle-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Compréhension</h6>
                                <span class="text-muted small">Extraction du sens profond et des directives réelles.</span>
                            </div>
                        </li>
                        <li class="mb-4 d-flex align-items-start">
                            <div class="bg-white rounded-circle shadow-sm me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:40px;height:40px;">
                                <i class="bi bi-2-circle-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Découpage sémantique</h6>
                                <span class="text-muted small">Axes → Actions → Activités → Livrables / Jalons.</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-start">
                            <div class="bg-white rounded-circle shadow-sm me-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:40px;height:40px;">
                                <i class="bi bi-3-circle-fill text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-success mb-1">Validation humaine</h6>
                                <span class="text-muted small">Vérifiez et corrigez la structure avant import en base.</span>
                            </div>
                        </li>
                    </ul>

                    <div class="alert alert-warning border-0 rounded-3 small mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Mode simulation :</strong> Si la clé API OpenAI n'est pas configurée,
                        un exemple fictif est généré. Les données de simulation seront clairement identifiées.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form      = document.getElementById('ai-form');
    const textarea  = document.getElementById('source_text');
    const counter   = document.getElementById('char-counter');
    const submitBtn = document.getElementById('submit-btn');
    const overlay   = document.getElementById('loader-overlay');

    // Compteur de caractères en temps réel
    function updateCounter() {
        const len = textarea.value.length;
        counter.textContent = len.toLocaleString('fr-FR') + ' / 80\u202f000';
        if (len > 80000 || (len > 0 && len < 50)) {
            counter.className = 'small text-danger fw-semibold';
        } else if (len >= 50) {
            counter.className = 'small text-success';
        } else {
            counter.className = 'small text-muted';
        }
    }
    textarea.addEventListener('input', updateCounter);
    updateCounter();

    // Soumission contrôlée — une seule fois, avec overlay
    form.addEventListener('submit', function (e) {
        const len = textarea.value.trim().length;

        if (!form.checkValidity()) {
            e.preventDefault();
            form.reportValidity();
            return;
        }
        if (len < 50) {
            e.preventDefault();
            textarea.classList.add('is-invalid');
            textarea.focus();
            return;
        }

        // Valide : afficher le loader, désactiver le bouton (évite double-submit)
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Analyse en cours...';
        overlay.classList.remove('d-none');
        overlay.classList.add('d-flex');
    });

    textarea.addEventListener('input', function () {
        textarea.classList.remove('is-invalid');
    });
});
</script>
@endsection
