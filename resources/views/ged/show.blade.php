@extends('layouts.app')

@section('title', 'Document — ' . $document->title)

@section('content')
<div class="container-fluid p-0">

    {{-- En-tête --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div class="d-flex align-items-start gap-3">
                    @php
                        $icons = ['pdf'=>'bi-file-earmark-pdf text-danger','doc'=>'bi-file-earmark-word text-primary','docx'=>'bi-file-earmark-word text-primary','xls'=>'bi-file-earmark-excel text-success','xlsx'=>'bi-file-earmark-excel text-success','ppt'=>'bi-file-earmark-slides text-warning','pptx'=>'bi-file-earmark-slides text-warning','jpg'=>'bi-file-earmark-image text-info','jpeg'=>'bi-file-earmark-image text-info','png'=>'bi-file-earmark-image text-info','zip'=>'bi-file-earmark-zip text-secondary'];
                        $icon = $icons[$document->type] ?? 'bi-file-earmark text-muted';
                    @endphp
                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" style="width:56px;height:56px;min-width:56px;">
                        <i class="bi {{ $icon }} fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $document->title }}</h4>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            @if($document->reference)
                                <span class="text-muted small"><i class="bi bi-hash me-1"></i>{{ $document->reference }}</span>
                            @endif
                            <span class="badge {{ $document->workflowStatusBadgeClass() }}">{{ $document->workflowStatusLabel() }}</span>
                            <span class="badge bg-light text-dark border">v{{ $document->version_label }}</span>
                            @if($document->is_main)
                                <span class="badge bg-primary-subtle text-primary">Document principal</span>
                            @endif
                            @if($document->is_physical_archive)
                                <span class="badge bg-warning-subtle text-warning">Archive physique</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    @can('download', $document)
                        <a href="{{ route('documents.download', $document) }}" class="btn btn-primary">
                            <i class="bi bi-download me-1"></i> Télécharger
                        </a>
                    @endcan
                    @can('update', $document)
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalReplaceFile">
                            <i class="bi bi-arrow-repeat me-1"></i> Nouvelle version
                        </button>
                    @endcan
                    @can('delete', $document)
                        <form method="POST" action="{{ route('documents.destroy', $document) }}"
                              onsubmit="return confirm('Confirmer la suppression de ce document ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Supprimer
                            </button>
                        </form>
                    @endcan
                    <a href="{{ route('ged.index') }}" class="btn btn-outline-light text-muted border">
                        <i class="bi bi-arrow-left me-1"></i> GED
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Colonne gauche — Métadonnées + Description + Tags --}}
        <div class="col-lg-8">

            {{-- Métadonnées principales --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-1 text-primary"></i> Informations documentaires</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($document->description)
                            <div class="col-12">
                                <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;letter-spacing:.06em;">Description</label>
                                <p class="mb-0">{{ $document->description }}</p>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Catégorie</label>
                            <p class="mb-0">{{ $document->categoryRef?->name ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Langue</label>
                            <p class="mb-0">{{ strtoupper($document->language ?? '—') }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Auteur / Service</label>
                            <p class="mb-0">{{ $document->author_service ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Date du document</label>
                            <p class="mb-0">{{ $document->document_date?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Date d'archivage</label>
                            <p class="mb-0">{{ $document->archived_at?->format('d/m/Y') ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Source</label>
                            <p class="mb-0">{{ $document->source_type ? ucfirst($document->source_type) : '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Format</label>
                            <p class="mb-0"><span class="badge bg-light text-dark border">{{ strtoupper($document->type) }}</span></p>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Taille</label>
                            <p class="mb-0">{{ $document->formattedSize() }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">MIME type</label>
                            <p class="mb-0 small text-muted">{{ $document->mime_type ?? '—' }}</p>
                        </div>
                        @if($document->confidentialityLevel)
                            <div class="col-md-4">
                                <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Confidentialité</label>
                                <p class="mb-0">
                                    <span class="badge bg-danger-subtle text-danger">{{ $document->confidentialityLevel->name }}</span>
                                </p>
                            </div>
                        @endif
                        @if($document->retentionRule)
                            <div class="col-md-4">
                                <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Conservation</label>
                                <p class="mb-0 small">{{ $document->retentionRule->name }}</p>
                            </div>
                        @endif
                        @if($document->decision)
                            <div class="col-12">
                                <label class="small text-muted text-uppercase fw-semibold" style="font-size:.7rem;">Décision associée</label>
                                <p class="mb-0">
                                    <a href="{{ route('decisions.show', $document->decision) }}" class="text-decoration-none">
                                        <i class="bi bi-link-45deg me-1"></i>
                                        {{ $document->decision->code }} — {{ Str::limit($document->decision->title, 80) }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            @if($document->tags->isNotEmpty())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-tags me-1 text-primary"></i> Mots-clés</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($document->tags as $tag)
                                <a href="{{ route('ged.index', ['q' => $tag->name]) }}"
                                   class="badge bg-light text-dark border text-decoration-none" style="font-size:.82rem;">
                                    # {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Références archive physique --}}
            @if($document->archiveReferences->isNotEmpty())
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-1 text-warning"></i> Références archive physique</h6>
                    </div>
                    <div class="card-body">
                        @foreach($document->archiveReferences as $ref)
                            <div class="d-flex gap-4 text-muted small mb-2">
                                @if($ref->box_code) <span><strong>Boîte :</strong> {{ $ref->box_code }}</span> @endif
                                @if($ref->shelf_code) <span><strong>Étagère :</strong> {{ $ref->shelf_code }}</span> @endif
                                @if($ref->room_code) <span><strong>Salle :</strong> {{ $ref->room_code }}</span> @endif
                                @if($ref->physical_reference) <span><strong>Réf. physique :</strong> {{ $ref->physical_reference }}</span> @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Historique des versions --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-1 text-primary"></i> Historique des versions</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($document->versions->sortByDesc('version_number') as $version)
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge {{ $version->is_major ? 'bg-primary' : 'bg-secondary' }}">
                                                v{{ $version->version_label }}
                                            </span>
                                            @if($version->is_major)
                                                <span class="badge bg-primary-subtle text-primary small">Version majeure</span>
                                            @endif
                                            <span class="text-muted small">
                                                {{ $version->created_at->format('d/m/Y à H:i') }}
                                            </span>
                                        </div>
                                        @if($version->change_reason)
                                            <p class="text-muted small mt-1 mb-0">{{ $version->change_reason }}</p>
                                        @endif
                                        <div class="text-muted small mt-1">
                                            <i class="bi bi-person me-1"></i>{{ $version->uploader?->name ?? 'Système' }}
                                            &nbsp;•&nbsp;
                                            {{ $version->size ? number_format($version->size / 1024, 1) . ' Ko' : '—' }}
                                            &nbsp;•&nbsp;
                                            <code class="small text-muted">{{ strtoupper($version->mime_type ?? '') }}</code>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-4 py-3 text-muted">Aucune version enregistrée.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>

        {{-- Colonne droite — Workflow + Journal --}}
        <div class="col-lg-4">

            {{-- Workflow de validation --}}
            @php $latestFlow = $document->validationFlows->sortByDesc('id')->first(); @endphp
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-diagram-3 me-1 text-primary"></i> Workflow de validation</h6>
                </div>
                <div class="card-body">
                    @if($latestFlow)
                        <div class="mb-3">
                            <span class="text-muted small">Circuit n°{{ $latestFlow->id }} — Statut :</span>
                            @php
                                $flowBadge = match($latestFlow->status) {
                                    'in_review' => 'bg-warning text-dark',
                                    'approved'  => 'bg-success',
                                    'rejected'  => 'bg-danger',
                                    default     => 'bg-secondary',
                                };
                                $flowLabel = match($latestFlow->status) {
                                    'in_review' => 'En cours',
                                    'approved'  => 'Approuvé',
                                    'rejected'  => 'Rejeté',
                                    default     => ucfirst($latestFlow->status),
                                };
                            @endphp
                            <span class="badge {{ $flowBadge }} ms-1">{{ $flowLabel }}</span>
                        </div>

                        <ul class="list-group list-group-flush">
                            @foreach($latestFlow->steps->sortBy('level') as $step)
                                @php
                                    $stepBadge = match($step->status) {
                                        'pending'  => 'bg-warning text-dark',
                                        'waiting'  => 'bg-light text-muted border',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default    => 'bg-secondary',
                                    };
                                    $stepLabel = match($step->status) {
                                        'pending'  => 'En attente',
                                        'waiting'  => 'En file',
                                        'approved' => 'Approuvé',
                                        'rejected' => 'Rejeté',
                                        default    => ucfirst($step->status),
                                    };
                                @endphp
                                <li class="list-group-item px-0 py-2 d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="small fw-semibold">
                                            Niveau {{ $step->level }}
                                            <span class="text-muted fw-normal">({{ $step->validator_role ?? 'Utilisateur assigné' }})</span>
                                        </div>
                                        @if($step->comment)
                                            <div class="text-muted" style="font-size:.78rem;">{{ $step->comment }}</div>
                                        @endif
                                        @if($step->acted_at)
                                            <div class="text-muted" style="font-size:.72rem;">
                                                {{ $step->validator?->name ?? '—' }} — {{ \Carbon\Carbon::parse($step->acted_at)->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="badge {{ $stepBadge }} ms-2">{{ $stepLabel }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small mb-3">Aucun workflow lancé pour ce document.</p>
                        @can('startValidation', $document)
                            @if(in_array($document->workflow_status, ['draft', 'rejected']))
                                <form method="POST" action="{{ route('ged.validations.start', $document) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-play-circle me-1"></i> Lancer la validation multi-niveaux
                                    </button>
                                </form>
                            @endif
                        @endcan
                    @endif
                </div>
            </div>

            {{-- Informations de dépôt --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-person me-1 text-primary"></i> Dépôt et traçabilité</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">Déposé par</dt>
                        <dd class="col-7">{{ $document->uploader?->name ?? 'Inconnu' }}</dd>

                        <dt class="col-5 text-muted">Date de dépôt</dt>
                        <dd class="col-7">{{ $document->uploaded_at?->format('d/m/Y H:i') ?? '—' }}</dd>

                        @if($document->validated_at)
                            <dt class="col-5 text-muted">Validé le</dt>
                            <dd class="col-7">{{ $document->validated_at->format('d/m/Y H:i') }}</dd>
                        @endif

                        <dt class="col-5 text-muted">Empreinte SHA-256</dt>
                        <dd class="col-7">
                            <code class="text-muted" style="font-size:.65rem;word-break:break-all;">
                                {{ $document->hash_sha256 }}
                            </code>
                        </dd>

                        @if($document->storageLocation)
                            <dt class="col-5 text-muted">Stockage</dt>
                            <dd class="col-7">{{ $document->storageLocation->name }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            {{-- Journal d'accès --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-shield-check me-1 text-primary"></i> Journal d'accès</h6>
                    <span class="badge bg-light text-muted border">{{ $document->accessLogs->count() }} entrées</span>
                </div>
                <div class="card-body p-0" style="max-height:320px;overflow-y:auto;">
                    <ul class="list-group list-group-flush">
                        @forelse($document->accessLogs as $log)
                            <li class="list-group-item px-3 py-2">
                                <div class="d-flex justify-content-between">
                                    <span class="small fw-semibold">
                                        @php
                                            $actionLabels = ['upload'=>'Dépôt','download'=>'Téléchargement','view'=>'Consultation','replace'=>'Remplacement','delete'=>'Suppression'];
                                        @endphp
                                        <span class="badge bg-light text-dark border me-1">
                                            {{ $actionLabels[$log->action] ?? ucfirst($log->action) }}
                                        </span>
                                        {{ $log->user?->name ?? 'Système' }}
                                    </span>
                                    <span class="text-muted small">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($log->ip_address)
                                    <div class="text-muted" style="font-size:.72rem;">IP : {{ $log->ip_address }}</div>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item px-3 py-3 text-muted text-center small">Aucune trace d'accès.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal — Remplacer le fichier (nouvelle version) --}}
@can('update', $document)
<div class="modal fade" id="modalReplaceFile" tabindex="-1" aria-labelledby="modalReplaceFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <input type="hidden" name="_redirect_ged" value="1">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalReplaceFileLabel">
                        <i class="bi bi-arrow-repeat me-2 text-primary"></i>Téléverser une nouvelle version
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nouveau fichier <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                            @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Max 50 Mo. Formats : PDF, Word, Excel, PowerPoint, Images, ZIP.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Titre <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $document->title) }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Motif de modification</label>
                            <input type="text" name="change_reason" class="form-control"
                                   placeholder="Ex : Correction suite validation..." value="{{ old('change_reason') }}">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="is_major_version" id="isMajorVersion" value="1"
                                       {{ old('is_major_version') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isMajorVersion">
                                    Incrémenter en version majeure (ex : v1.0 → v2.0)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload me-1"></i> Téléverser la nouvelle version
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

@endsection
