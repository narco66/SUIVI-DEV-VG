@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Décisions Communautaires</h2>
            <p class="text-muted mb-0">Gestion et suivi de la mise en œuvre</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('decisions.export', request()->query()) }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-download me-2"></i> Exporter (.xlsx)
            </a>
            <a href="{{ route('decisions.create') }}" class="btn btn-primary px-4">
                <i class="bi bi-plus-lg me-2"></i> Nouvelle Décision
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4 rounded-4 bg-light bg-opacity-50">
        <div class="card-body p-4">
            <h6 class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em;"><i class="bi bi-funnel me-1"></i> Filtres de recherche</h6>
            <form method="GET" action="{{ route('decisions.index') }}" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 py-2" placeholder="Rechercher par titre ou référence..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select py-2">
                        <option value="">Tous les statuts</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours d'exécution</option>
                        <option value="delayed" {{ request('status') == 'delayed' ? 'selected' : '' }}>En retard</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Achevée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 py-2"><i class="bi bi-filter"></i> Filtrer</button>
                </div>
                <div class="col-md-2 text-center">
                    <a href="{{ route('decisions.index') }}" class="text-muted text-decoration-none small hover-primary"><i class="bi bi-x-circle me-1"></i>Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden fade-in" style="animation-delay: 0.1s;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-borderless">
                <thead class="bg-light text-muted small text-uppercase" style="letter-spacing: 0.03em;">
                    <tr class="border-bottom">
                        <th class="ps-4 py-3 fw-medium">Référence & Titre</th>
                        <th class="py-3 fw-medium">Type</th>
                        <th class="py-3 fw-medium">Dates (Adoption / Échéance)</th>
                        <th class="py-3 fw-medium">Avancement</th>
                        <th class="py-3 fw-medium">Statut</th>
                        <th class="pe-4 py-3 text-end fw-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($decisions as $dec)
                    <tr class="border-bottom border-light hover-bg-light transition-all">
                        <td class="ps-4 py-3">
                            <span class="fw-bold text-dark d-block mb-1">{{ $dec->code }}</span>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 300px;" title="{{ $dec->title }}">{{ $dec->title }}</small>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 fw-normal">{{ $dec->type->name ?? 'N/A' }}</span>
                        </td>
                        <td class="py-3">
                            <div class="d-flex flex-column">
                                <span class="small text-dark mb-1"><i class="bi bi-calendar-check text-success me-1"></i>{{ $dec->date_adoption ? $dec->date_adoption->format('d/m/Y') : '-' }}</span>
                                <span class="small {{ $dec->date_echeance && $dec->date_echeance < now() && !in_array($dec->status, ['completed', 'closed']) ? 'text-danger fw-bold' : 'text-muted' }}">
                                    <i class="bi bi-flag-fill {{ $dec->date_echeance && $dec->date_echeance < now() && !in_array($dec->status, ['completed', 'closed']) ? 'text-danger' : 'text-muted opacity-50' }} me-1"></i>
                                    {{ $dec->date_echeance ? $dec->date_echeance->format('d/m/Y') : '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="py-3" style="min-width: 150px;">
                            <div class="d-flex align-items-center mb-1">
                                <span class="me-auto text-dark fw-bold small">{{ config('app.locale') == 'fr' ? number_format($dec->progress_rate, 0) : $dec->progress_rate }}%</span>
                            </div>
                            <div class="progress bg-light" style="height: 6px;">
                                <div class="progress-bar {{ $dec->progress_rate == 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ $dec->progress_rate }}%" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td class="py-3">
                            @if($dec->status == 'completed')
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-check-circle me-1"></i>Achevée</span>
                            @elseif($dec->status == 'in_progress')
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill"><i class="bi bi-arrow-repeat me-1"></i>En exécution</span>
                            @elseif($dec->status == 'delayed')
                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill"><i class="bi bi-exclamation-circle me-1"></i>En retard</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">{{ ucfirst($dec->status) }}</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                <a href="{{ route('decisions.show', $dec->id) }}" class="btn btn-sm btn-light text-primary py-2 px-3 hover-elevate" title="Détails" style="border: none;"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('decisions.edit', $dec->id) }}" class="btn btn-sm btn-light text-secondary py-2 px-3 hover-elevate" title="Modifier" style="border: none; border-left: 1px solid #dee2e6;"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-folder2-open fs-1 text-light"></i></div>
                            Aucune décision trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($decisions->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $decisions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
