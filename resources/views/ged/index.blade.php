@extends('layouts.app')

@section('title', 'GED — Gestion électronique des documents')

@section('content')
<div class="container-fluid p-0">

    {{-- En-tête --}}
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-lg me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm d-flex align-items-center justify-content-center" style="width:52px;height:52px;">
                        <i class="bi bi-folder2-open fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size:.72rem;letter-spacing:.08em;">Module GED</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Gestion électronique des documents</h2>
                        <p class="text-muted mb-0">Archivage institutionnel — Recherche multicritère — Versioning — Traçabilité</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-auto d-flex gap-2 align-items-center">
            @can('viewAny', App\Models\Document::class)
                <a href="{{ route('ged.validations.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-patch-check me-1"></i> Validations
                </a>
                <a href="{{ route('ged.export', request()->query()) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-download me-1"></i> Exporter CSV
                </a>
            @endcan
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-2"><i class="bi bi-files fs-5 text-primary"></i></div>
                    <div><div class="small text-muted">Total documents</div><div class="h5 mb-0 fw-bold">{{ number_format($stats['total']) }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-success bg-opacity-10 p-2"><i class="bi bi-check-circle fs-5 text-success"></i></div>
                    <div><div class="small text-muted">Publiés</div><div class="h5 mb-0 fw-bold">{{ number_format($stats['published']) }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-secondary bg-opacity-10 p-2"><i class="bi bi-archive fs-5 text-secondary"></i></div>
                    <div><div class="small text-muted">Archivés</div><div class="h5 mb-0 fw-bold">{{ number_format($stats['archived']) }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-2"><i class="bi bi-hourglass-split fs-5 text-warning"></i></div>
                    <div><div class="small text-muted">En revue</div><div class="h5 mb-0 fw-bold">{{ number_format($stats['in_review']) }}</div></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-danger bg-opacity-10 p-2"><i class="bi bi-lock fs-5 text-danger"></i></div>
                    <div><div class="small text-muted">Classifiés</div><div class="h5 mb-0 fw-bold">{{ number_format($stats['confidential']) }}</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres avancés --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-semibold text-muted"><i class="bi bi-funnel me-1"></i> Filtres de recherche</h6>
        </div>
        <div class="card-body pb-2">
            <form method="GET" action="{{ route('ged.index') }}" id="ged-filter-form">
                <div class="row g-2">
                    {{-- Recherche plein texte --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label small text-muted mb-1">Recherche</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="q" class="form-control border-start-0"
                                   placeholder="Titre, référence, description..."
                                   value="{{ request('q') }}">
                        </div>
                    </div>

                    {{-- Catégorie --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">Catégorie</label>
                        <select name="document_category_id" class="form-select">
                            <option value="">Toutes</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('document_category_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Statut workflow --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">Statut</label>
                        <select name="workflow_status" class="form-select">
                            <option value="">Tous</option>
                            @foreach($workflowStatuses as $val => $label)
                                <option value="{{ $val }}" @selected(request('workflow_status') === $val)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Type de fichier --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">Type fichier</label>
                        <select name="type" class="form-select">
                            <option value="">Tous</option>
                            @foreach($fileTypes as $ft)
                                <option value="{{ $ft }}" @selected(request('type') === $ft)>
                                    {{ strtoupper($ft) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Confidentialité --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">Confidentialité</label>
                        <select name="confidentiality_level_id" class="form-select">
                            <option value="">Tous niveaux</option>
                            @foreach($confidentialityLevels as $level)
                                <option value="{{ $level->id }}" @selected(request('confidentiality_level_id') == $level->id)>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date de (document_date) --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">Date document — de</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    {{-- Date à --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">Date document — à</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    {{-- Décision liée --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">N° décision</label>
                        <input type="number" name="decision_id" class="form-control"
                               placeholder="ID décision"
                               value="{{ request('decision_id') }}">
                    </div>

                    {{-- Auteur / service --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">Service émetteur</label>
                        <input type="text" name="author_service" class="form-control"
                               placeholder="Direction, département..."
                               value="{{ request('author_service') }}">
                    </div>

                    {{-- Tri --}}
                    <div class="col-6 col-md-2">
                        <label class="form-label small text-muted mb-1">Trier par</label>
                        <select name="sort" class="form-select">
                            <option value="uploaded_at" @selected(request('sort','uploaded_at') === 'uploaded_at')>Date de dépôt</option>
                            <option value="document_date" @selected(request('sort') === 'document_date')>Date document</option>
                            <option value="title" @selected(request('sort') === 'title')>Titre</option>
                            <option value="size" @selected(request('sort') === 'size')>Taille</option>
                            <option value="type" @selected(request('sort') === 'type')>Type</option>
                        </select>
                    </div>

                    {{-- Direction tri --}}
                    <div class="col-6 col-md-1">
                        <label class="form-label small text-muted mb-1">Ordre</label>
                        <select name="dir" class="form-select">
                            <option value="desc" @selected(request('dir','desc') === 'desc')>↓ Déc.</option>
                            <option value="asc" @selected(request('dir') === 'asc')>↑ Asc.</option>
                        </select>
                    </div>

                    {{-- Boutons --}}
                    <div class="col-12 col-md-auto d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i> Rechercher
                        </button>
                        <a href="{{ route('ged.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tableau des documents --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <span class="text-muted small">
                <strong>{{ $documents->total() }}</strong> document(s) trouvé(s)
            </span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-3" style="width:30px;"></th>
                        <th>Document</th>
                        <th>Référence</th>
                        <th>Catégorie</th>
                        <th>Décision</th>
                        <th>Version</th>
                        <th>Statut</th>
                        <th>Confidentialité</th>
                        <th>Taille</th>
                        <th>Déposé le</th>
                        <th class="pe-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td class="ps-3 text-center">
                            @php
                                $icons = ['pdf'=>'bi-file-earmark-pdf text-danger','doc'=>'bi-file-earmark-word text-primary','docx'=>'bi-file-earmark-word text-primary','xls'=>'bi-file-earmark-excel text-success','xlsx'=>'bi-file-earmark-excel text-success','ppt'=>'bi-file-earmark-slides text-warning','pptx'=>'bi-file-earmark-slides text-warning','jpg'=>'bi-file-earmark-image text-info','jpeg'=>'bi-file-earmark-image text-info','png'=>'bi-file-earmark-image text-info','zip'=>'bi-file-earmark-zip text-secondary'];
                                $icon = $icons[$doc->type] ?? 'bi-file-earmark text-muted';
                            @endphp
                            <i class="bi {{ $icon }} fs-5"></i>
                        </td>
                        <td>
                            <a href="{{ route('ged.show', $doc) }}" class="fw-semibold text-dark text-decoration-none">
                                {{ Str::limit($doc->title, 60) }}
                            </a>
                            @if($doc->is_main)
                                <span class="badge bg-primary-subtle text-primary ms-1 small">Principal</span>
                            @endif
                            @if($doc->is_physical_archive)
                                <span class="badge bg-warning-subtle text-warning ms-1 small">Archive physique</span>
                            @endif
                            @if($doc->tags->isNotEmpty())
                                <div class="mt-1">
                                    @foreach($doc->tags->take(3) as $tag)
                                        <span class="badge bg-light text-muted border" style="font-size:.7rem;">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $doc->reference ?: '—' }}</td>
                        <td class="small">{{ $doc->categoryRef?->name ?: '—' }}</td>
                        <td class="small">
                            @if($doc->decision)
                                <a href="{{ route('decisions.show', $doc->decision) }}" class="text-decoration-none text-muted">
                                    {{ $doc->decision->code }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="small text-center">
                            <span class="badge bg-light text-dark border">v{{ $doc->version_label }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $doc->workflowStatusBadgeClass() }}">
                                {{ $doc->workflowStatusLabel() }}
                            </span>
                        </td>
                        <td class="small">
                            @if($doc->confidentialityLevel)
                                <span class="badge bg-danger-subtle text-danger">{{ $doc->confidentialityLevel->name }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $doc->formattedSize() }}</td>
                        <td class="small text-muted">{{ $doc->uploaded_at?->format('d/m/Y') ?: '—' }}</td>
                        <td class="pe-3 text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('ged.show', $doc) }}"
                                   class="btn btn-sm btn-outline-primary" title="Voir la fiche">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('download', $doc)
                                    <a href="{{ route('documents.download', $doc) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Télécharger">
                                        <i class="bi bi-download"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-muted opacity-50"></i>
                            Aucun document ne correspond aux critères sélectionnés.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
            <span class="text-muted small">
                Page {{ $documents->currentPage() }} / {{ $documents->lastPage() }}
            </span>
            {{ $documents->links() }}
        </div>
    </div>

</div>
@endsection
