@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-diagram-3-fill fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Planification</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Plans d'Action</h2>
                        <p class="text-muted mb-2">Suivi de la planification opérationnelle</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">
                            {{ $actionPlans->total() }} plan(s) suivi(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto mt-0 d-flex align-items-center">
            <a href="{{ route('action-plans.create') }}" class="btn btn-primary px-4 py-2 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i> Nouveau Plan d'Action
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('action-plans.index') }}" class="row g-3">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 bg-light" placeholder="Rechercher par titre..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-5">
                    <select name="decision_id" class="form-select bg-light">
                        <option value="">Toutes les décisions associées</option>
                        @foreach($decisions as $dec)
                        <option value="{{ $dec->id }}" {{ request('decision_id') == $dec->id ? 'selected' : '' }}>{{ $dec->code }} - {{ Str::limit($dec->title, 40) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Titre du Plan</th>
                        <th class="py-3">Décision Source</th>
                        <th class="py-3">Période</th>
                        <th class="py-3">Statut</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actionPlans as $plan)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-semibold text-dark d-block">{{ Str::limit($plan->title, 60) }}</span>
                        </td>
                        <td>
                            @if($plan->decision)
                            <a href="{{ route('decisions.show', $plan->decision_id) }}" class="text-decoration-none fw-medium">{{ $plan->decision->code }}</a>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">
                                {{ $plan->start_date ? $plan->start_date->format('d/m/Y') : '-' }} au 
                                {{ $plan->end_date ? $plan->end_date->format('d/m/Y') : '-' }}
                            </span>
                        </td>
                        <td>
                            @if($plan->status == 'completed')
                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill">Achevé</span>
                            @elseif($plan->status == 'active')
                                <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">Actif</span>
                            @elseif($plan->status == 'suspended')
                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill">Suspendu</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded-pill">Brouillon</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <a href="{{ route('action-plans.show', $plan->id) }}" class="btn btn-sm btn-light border" title="Détails (Axes, Actions, etc.)"><i class="bi bi-list-nested"></i> Structure</a>
                                <a href="{{ route('action-plans.edit', $plan->id) }}" class="btn btn-sm btn-light border" title="Modifier"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-diagram-3 fs-1 text-light"></i></div>
                            Aucun plan d'action trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($actionPlans->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $actionPlans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

