@extends('layouts.app')

@section('title', 'Prévisualisation de l\'analyse IA')

@section('content')
<div class="container-fluid p-0">

    {{-- En-tête --}}
    <div class="row align-items-start mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="bi bi-robot text-success me-3 fs-2"></i> Prévisualisation de l'analyse
            </h2>
            <p class="text-muted mb-0">Vérifiez l'arborescence extraite par l'IA avant importation en base de données.</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex flex-wrap gap-2 align-items-center">

            {{-- Badge simulation --}}
            @if($aiAnalysis->status === 'simulated')
                <span class="badge bg-warning text-dark py-2 px-3 fs-6">
                    <i class="bi bi-exclamation-triangle me-1"></i> Mode SIMULATION — Données fictives
                </span>
            @endif

            {{-- Score de confiance --}}
            @if($aiAnalysis->confidence_score !== null)
                <span class="badge bg-light text-dark border py-2 px-3 fw-medium">
                    Confiance IA :
                    <b class="{{ $aiAnalysis->confidence_score >= 80 ? 'text-success' : 'text-warning' }}">
                        {{ $aiAnalysis->confidence_score }}%
                    </b>
                </span>
            @endif

            {{-- Tokens utilisés --}}
            @if($aiAnalysis->tokens_used)
                <span class="badge bg-light text-muted border py-2 px-3">
                    <i class="bi bi-cpu me-1"></i>{{ number_format($aiAnalysis->tokens_used) }} tokens
                </span>
            @endif

            {{-- Bouton Refaire l'analyse --}}
            <a href="{{ route('ai-analyses.create') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Refaire
            </a>

            {{-- Bouton Valider et Importer --}}
            @if($aiAnalysis->status !== 'finalized')
                <form action="{{ route('ai-analyses.confirm', $aiAnalysis->id) }}" method="POST" id="confirm-form">
                    @csrf
                    <input type="hidden" name="edited_json" id="edited_json"
                           value="{{ htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') }}">
                    <button type="button" class="btn btn-success px-4 rounded-pill shadow-sm fw-semibold"
                            id="confirm-btn" onclick="confirmImport()">
                        <i class="bi bi-check2-circle me-2"></i> Valider et importer
                    </button>
                </form>
            @else
                <span class="badge bg-success py-2 px-3 fs-6">
                    <i class="bi bi-check-circle me-1"></i> Déjà importée
                </span>
            @endif
        </div>
    </div>

    {{-- Alerte simulation --}}
    @if($aiAnalysis->status === 'simulated')
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4">
            <h6 class="fw-bold d-flex align-items-center mb-1">
                <i class="bi bi-robot me-2 fs-5"></i> Données de simulation
            </h6>
            <p class="mb-0 small">
                L'API OpenAI n'était pas disponible lors de cette analyse. Les données ci-dessous sont <strong>fictives</strong>
                et générées automatiquement à des fins de démonstration.
                Pour une analyse réelle, configurez la clé <code>OPENAI_API_KEY</code> dans le fichier <code>.env</code>.
            </p>
        </div>
    @endif

    {{-- Avertissements IA --}}
    @if(!empty($data['warnings']))
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4">
            <h6 class="fw-bold d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i> Points d'attention signalés par l'IA
            </h6>
            <ul class="mb-0 small">
                @foreach($data['warnings'] as $warning)
                    <li>{{ $warning }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Messages flash --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Informations générales --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Type d'acte</span>
                            <span class="fw-bold fs-5 text-dark">{{ $data['document_type'] ?? 'Inconnu' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Titre identifié</span>
                            <span class="fw-bold text-dark">{{ $data['document_title'] ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Référence</span>
                            <span class="fw-bold text-dark">{{ $data['document_reference'] ?? 'N/A' }}</span>
                        </div>
                        @if(!empty($data['summary']))
                            <div class="col-12 pt-2 border-top">
                                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Résumé généré</span>
                                <p class="mb-0 text-dark">{{ $data['summary'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Compteurs --}}
        @php
            $axesCount      = count($data['axes_strategiques'] ?? []);
            $actionsCount   = collect($data['axes_strategiques'] ?? [])->sum(fn($a) => count($a['actions_metiers'] ?? []));
            $activitesCount = collect($data['axes_strategiques'] ?? [])->sum(fn($a) =>
                collect($a['actions_metiers'] ?? [])->sum(fn($am) => count($am['activites'] ?? []))
            );
            $livrablesCount = collect($data['axes_strategiques'] ?? [])->sum(fn($a) =>
                collect($a['actions_metiers'] ?? [])->sum(fn($am) =>
                    collect($am['activites'] ?? [])->sum(fn($act) => count($act['livrables'] ?? []))
                )
            );
            $jalonsCount    = collect($data['axes_strategiques'] ?? [])->sum(fn($a) =>
                collect($a['actions_metiers'] ?? [])->sum(fn($am) =>
                    collect($am['activites'] ?? [])->sum(fn($act) => count($act['jalons'] ?? []))
                )
            );
        @endphp
        <div class="col-12">
            <div class="row g-3">
                @foreach([
                    ['Axes', $axesCount, 'bi-diagram-3-fill', 'primary'],
                    ['Actions', $actionsCount, 'bi-bullseye', 'danger'],
                    ['Activités', $activitesCount, 'bi-check-square', 'success'],
                    ['Livrables', $livrablesCount, 'bi-box', 'info'],
                    ['Jalons', $jalonsCount, 'bi-flag', 'warning'],
                ] as [$label, $count, $icon, $color])
                    <div class="col-6 col-md">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 py-3">
                                <div class="rounded-3 bg-{{ $color }} bg-opacity-10 p-2">
                                    <i class="bi {{ $icon }} text-{{ $color }} fs-5"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">{{ $label }}</div>
                                    <div class="h5 mb-0 fw-bold">{{ $count }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Arborescence interactive --}}
        <div class="col-12">
            <h5 class="fw-bold text-dark mb-3">
                <i class="bi bi-tree me-2 text-primary"></i>Arborescence de suivi extraite
            </h5>

            @if(isset($data['axes_strategiques']) && is_array($data['axes_strategiques']) && count($data['axes_strategiques']) > 0)
                <div class="accordion" id="accordionAxes">
                    @foreach($data['axes_strategiques'] as $axeIndex => $axe)
                        <div class="accordion-item border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                            <h2 class="accordion-header" id="headingAxe{{ $axeIndex }}">
                                <button class="accordion-button {{ $axeIndex > 0 ? 'collapsed' : '' }} bg-light fw-bold text-dark"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseAxe{{ $axeIndex }}"
                                        aria-expanded="{{ $axeIndex === 0 ? 'true' : 'false' }}"
                                        aria-controls="collapseAxe{{ $axeIndex }}">
                                    <i class="bi bi-diagram-3-fill text-primary me-2"></i>
                                    AXE {{ $axeIndex + 1 }} — {{ $axe['title'] ?? 'Sans nom' }}
                                    @if(!empty($axe['priority']))
                                        @php $p = $axe['priority']; @endphp
                                        <span class="badge ms-2 {{ $p === 'high' ? 'bg-danger' : ($p === 'medium' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ $p === 'high' ? 'Haute' : ($p === 'medium' ? 'Moyenne' : 'Basse') }}
                                        </span>
                                    @endif
                                </button>
                            </h2>
                            <div id="collapseAxe{{ $axeIndex }}"
                                 class="accordion-collapse collapse {{ $axeIndex === 0 ? 'show' : '' }}"
                                 aria-labelledby="headingAxe{{ $axeIndex }}"
                                 data-bs-parent="#accordionAxes">
                                <div class="accordion-body p-4 bg-white">
                                    @if(!empty($axe['description']))
                                        <p class="text-muted small mb-4 border-start border-primary border-3 ps-3">
                                            {{ $axe['description'] }}
                                        </p>
                                    @endif

                                    @foreach((array)($axe['actions_metiers'] ?? []) as $actionIndex => $action)
                                        <div class="card border shadow-sm mb-4">
                                            <div class="card-header bg-white d-flex justify-content-between align-items-start gap-2 py-3">
                                                <h6 class="fw-bold text-dark mb-0">
                                                    <i class="bi bi-bullseye text-danger me-2"></i>
                                                    Action {{ $axeIndex + 1 }}.{{ $actionIndex + 1 }} — {{ $action['title'] ?? 'Sans nom' }}
                                                </h6>
                                                @if(!empty($action['responsable_presume']))
                                                    <span class="badge bg-secondary-subtle text-secondary border flex-shrink-0">
                                                        <i class="bi bi-person me-1"></i>{{ $action['responsable_presume'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if(!empty($action['description']))
                                                <div class="card-body pb-0">
                                                    <p class="text-muted small mb-3">{{ $action['description'] }}</p>
                                                </div>
                                            @endif

                                            {{-- Activités --}}
                                            @if(!empty($action['activites']))
                                                <div class="card-body pt-0">
                                                    <div class="row g-3">
                                                        @foreach((array)$action['activites'] as $actIndex => $activite)
                                                            <div class="col-12 col-xl-6">
                                                                <div class="p-3 border rounded-3 bg-light h-100">
                                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                                        <h6 class="fw-bold text-dark mb-0 small">
                                                                            <i class="bi bi-check-square text-success me-1"></i>
                                                                            {{ $activite['title'] ?? 'Activité' }}
                                                                        </h6>
                                                                    </div>
                                                                    @if(!empty($activite['description']))
                                                                        <p class="text-muted mb-2" style="font-size:.8rem;">
                                                                            {{ $activite['description'] }}
                                                                        </p>
                                                                    @endif
                                                                    <div class="d-flex flex-wrap gap-1 mb-3">
                                                                        @if(!empty($activite['acteur_presume']))
                                                                            <span class="badge bg-light border text-muted" style="font-size:.7rem;">
                                                                                <i class="bi bi-person me-1"></i>{{ $activite['acteur_presume'] }}
                                                                            </span>
                                                                        @endif
                                                                        @if(!empty($activite['duree_estimee']))
                                                                            <span class="badge border border-secondary text-secondary" style="font-size:.7rem;">
                                                                                <i class="bi bi-clock me-1"></i>{{ $activite['duree_estimee'] }}
                                                                            </span>
                                                                        @endif
                                                                    </div>

                                                                    {{-- Livrables --}}
                                                                    @if(!empty($activite['livrables']))
                                                                        <div class="mb-2">
                                                                            <div class="fw-semibold text-info small mb-1">
                                                                                <i class="bi bi-box me-1"></i>Livrables ({{ count($activite['livrables']) }})
                                                                            </div>
                                                                            @foreach((array)$activite['livrables'] as $liv)
                                                                                <div class="ps-2 border-start border-info mb-1" style="font-size:.78rem;">
                                                                                    <span class="fw-semibold">{{ $liv['title'] ?? '—' }}</span>
                                                                                    @if(!empty($liv['type']))
                                                                                        <span class="badge bg-info-subtle text-info ms-1">{{ $liv['type'] }}</span>
                                                                                    @endif
                                                                                    @if(!empty($liv['description']))
                                                                                        <div class="text-muted">{{ $liv['description'] }}</div>
                                                                                    @endif
                                                                                    @if(!empty($liv['preuve_attendue']))
                                                                                        <div class="text-muted fst-italic">Preuve : {{ $liv['preuve_attendue'] }}</div>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif

                                                                    {{-- Jalons --}}
                                                                    @if(!empty($activite['jalons']))
                                                                        <div>
                                                                            <div class="fw-semibold text-warning small mb-1">
                                                                                <i class="bi bi-flag me-1"></i>Jalons ({{ count($activite['jalons']) }})
                                                                            </div>
                                                                            @foreach((array)$activite['jalons'] as $jalon)
                                                                                <div class="ps-2 border-start border-warning mb-1" style="font-size:.78rem;">
                                                                                    <span class="fw-semibold">{{ $jalon['title'] ?? '—' }}</span>
                                                                                    @if(!empty($jalon['echeance_estimee']))
                                                                                        <span class="text-muted ms-1">— {{ $jalon['echeance_estimee'] }}</span>
                                                                                    @endif
                                                                                    @if(!empty($jalon['importance']))
                                                                                        @php $imp = $jalon['importance']; @endphp
                                                                                        <span class="badge ms-1 {{ $imp === 'high' ? 'bg-danger' : ($imp === 'medium' ? 'bg-warning text-dark' : 'bg-secondary') }}" style="font-size:.65rem;">
                                                                                            {{ $imp === 'high' ? 'Critique' : ($imp === 'medium' ? 'Moyen' : 'Bas') }}
                                                                                        </span>
                                                                                    @endif
                                                                                    @if(!empty($jalon['description']))
                                                                                        <div class="text-muted">{{ $jalon['description'] }}</div>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info border-0 shadow-sm rounded-4">
                    <i class="bi bi-info-circle me-2"></i>
                    L'IA n'a détecté aucun axe stratégique clairement formulé.
                    Vous pourrez structurer le plan d'action manuellement après import.
                </div>
            @endif
        </div>

    </div>

    {{-- Overlay de chargement pour la confirmation --}}
    <div id="confirm-overlay"
         class="position-fixed top-0 start-0 w-100 h-100 bg-white bg-opacity-90 d-none flex-column align-items-center justify-content-center"
         style="z-index:1050;">
        <div class="spinner-border text-success border-4 mb-3" role="status" style="width:3rem;height:3rem;"></div>
        <h5 class="fw-bold text-dark">Création de l'arborescence en cours...</h5>
        <p class="text-muted mb-0">Les données sont importées en base de données. Veuillez patienter.</p>
    </div>
</div>

<script>
function confirmImport() {
    const nb = {{ $axesCount }} + {{ $actionsCount }} + {{ $activitesCount }};
    const msg = `Confirmez-vous l'import de cette structure ?\n\n`
              + `{{ $axesCount }} axe(s) · {{ $actionsCount }} action(s) · {{ $activitesCount }} activité(s) · {{ $livrablesCount }} livrable(s) · {{ $jalonsCount }} jalon(s)\n\n`
              + `La décision sera créée en brouillon. Vous pourrez affecter les responsables manuellement.`;

    if (!confirm(msg)) return;

    // Afficher l'overlay et désactiver le bouton avant soumission
    const btn = document.getElementById('confirm-btn');
    const overlay = document.getElementById('confirm-overlay');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Import en cours...';
    overlay.classList.remove('d-none');
    overlay.classList.add('d-flex');

    document.getElementById('confirm-form').submit();
}
</script>
@endsection
