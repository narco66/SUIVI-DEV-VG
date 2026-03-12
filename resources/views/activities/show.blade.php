@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Détails de l'Activité</h2>
            <p class="text-muted mb-0">Code : <span class="fw-medium">{{ $activity->code }}</span></p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('action-plans.show', $activity->action->strategicAxis->action_plan_id) }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i> Retour au Plan
            </a>
            <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-primary px-4">
                <i class="bi bi-pencil me-2"></i> Modifier
            </a>
        </div>
    </div>

    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold mb-2">{{ $activity->title }}</h4>
                    <div class="d-flex gap-4 text-muted small mt-3">
                        <span><i class="bi bi-briefcase me-1"></i> Direction : <strong class="text-dark">{{ $activity->direction->name ?? 'Non définie' }}</strong></span>
                        <span><i class="bi bi-calendar-range me-1"></i> Période : <strong class="text-dark">{{ $activity->start_date ? $activity->start_date->format('d/m/Y') : '-' }} au {{ $activity->end_date ? $activity->end_date->format('d/m/Y') : '-' }}</strong></span>
                        @if($activity->budget)
                            <span><i class="bi bi-cash-stack me-1"></i> Budget : <strong class="text-dark">{{ number_format($activity->budget, 2, ',', ' ') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge {{ $activity->status == 'completed' ? 'bg-success' : ($activity->status == 'in_progress' ? 'bg-primary' : 'bg-secondary') }} px-3 py-2 fs-6 rounded-pill mb-2 d-inline-block">
                        {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                    </span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted fw-medium">Avancement :</span>
                        <div class="progress flex-grow-1" style="height: 8px; width: 100px;">
                            <div class="progress-bar {{ $activity->progress_rate == 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ $activity->progress_rate }}%"></div>
                        </div>
                        <strong class="small">{{ $activity->progress_rate }}%</strong>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('progress-updates.create', ['type' => 'App\Models\Activity', 'id' => $activity->id]) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                            <i class="bi bi-graph-up-arrow me-1"></i> Déclarer un avancement
                        </a>
                    </div>
                </div>
            </div>

            @if($activity->description)
            <div class="mb-4">
                <h6 class="text-uppercase fw-bold text-muted mb-2" style="font-size: 0.8rem;">Description Manuelle</h6>
                <p class="text-dark">{{ $activity->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Milestones Column -->
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-flag text-primary me-2"></i> Jalons (Milestones)</h5>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createMilestoneModal"><i class="bi bi-plus"></i> Nouveau</button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($activity->milestones as $milestone)
                            <li class="list-group-item p-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-semibold {{ $milestone->status == 'achieved' ? 'text-decoration-line-through text-muted' : '' }}">{{ $milestone->title }}</h6>
                                    <small class="text-muted d-flex gap-3">
                                        <span>Cible : {{ $milestone->expected_date ? $milestone->expected_date->format('d/m/Y') : 'N/A' }}</span>
                                        @if($milestone->status == 'achieved' && $milestone->achieved_date)
                                            <span class="text-success"><i class="bi bi-check2-all me-1"></i> Réalisé le {{ $milestone->achieved_date->format('d/m/Y') }}</span>
                                        @endif
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge {{ $milestone->status == 'achieved' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill">{{ $milestone->status == 'achieved' ? 'Atteint' : 'En attente' }}</span>
                                    <form action="{{ route('milestones.destroy', $milestone->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce jalon ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item p-4 text-center text-muted col-12 border-0">Aucun jalon défini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Deliverables Column -->
        <div class="col-12 col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam text-success me-2"></i> Livrables Attendus</h5>
                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#createDeliverableModal"><i class="bi bi-plus"></i> Nouveau</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-3 border-0 rounded-start">Livrable</th>
                                    <th class="border-0">Échéance</th>
                                    <th class="border-0">Statut</th>
                                    <th class="pe-3 text-end border-0 rounded-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activity->deliverables as $deliverable)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="fw-semibold {{ $deliverable->status == 'delivered' ? 'text-decoration-line-through text-muted' : '' }}">{{ $deliverable->title }}</span>
                                            @if($deliverable->description)
                                                <small class="d-block text-muted text-truncate" style="max-width: 200px;" title="{{ $deliverable->description }}">{{ $deliverable->description }}</small>
                                            @endif
                                        </td>
                                        <td class="small">{{ $deliverable->expected_date ? $deliverable->expected_date->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <span class="badge {{ $deliverable->status == 'delivered' ? 'bg-success' : 'bg-secondary' }}">{{ $deliverable->status == 'delivered' ? 'Livré' : 'En attente' }}</span>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <form action="{{ route('deliverables.destroy', $deliverable->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce livrable ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted border-0">Aucun livrable défini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Indicateurs de Performance (Impact) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-speedometer2 text-info me-2"></i> Indicateurs de Performance (Impact)</h5>
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#createIndicatorModal">
                        <i class="bi bi-plus-circle"></i> Ajouter un Indicateur
                    </button>
                </div>
                <div class="card-body p-4">
                    @if($activity->indicators->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-bar-chart d-block mb-3 fs-1 text-light"></i>
                            Aucun indicateur de performance défini pour l'instant. Ajoutez-en un pour mesurer l'impact réel.
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($activity->indicators as $indicator)
                                @php
                                    $percentage = $indicator->target_value > 0 ? min(100, ($indicator->current_value / $indicator->target_value) * 100) : 0;
                                @endphp
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="border rounded p-3 bg-light position-relative">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $indicator->title }}</h6>
                                            <form action="{{ route('indicators.destroy', $indicator->id) }}" method="POST" onsubmit="return confirm('Supprimer cet indicateur ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger p-0" title="Supprimer"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                        <p class="small text-muted mb-3">Unité : <strong>{{ $indicator->unit }}</strong></p>
                                        
                                        <div class="d-flex justify-content-between align-items-end mb-1">
                                            <span class="fs-4 fw-bold text-primary">{{ number_format($indicator->current_value, 2, ',', ' ') }}</span>
                                            <span class="small text-muted">Cible : {{ number_format($indicator->target_value, 2, ',', ' ') }}</span>
                                        </div>
                                        
                                        <div class="progress mt-2 mb-3" style="height: 10px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        
                                        <div class="text-end">
                                            <button class="btn btn-sm btn-light border text-primary w-100" data-bs-toggle="modal" data-bs-target="#updateIndicatorModal-{{ $indicator->id }}">
                                                <i class="bi bi-arrow-repeat me-1"></i> Mettre à jour la valeur
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Update Indicator -->
                                <div class="modal fade" id="updateIndicatorModal-{{ $indicator->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <form method="POST" action="{{ route('indicators.update', $indicator->id) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h6 class="modal-title fw-bold text-truncate" title="{{ $indicator->title }}">{{ $indicator->title }}</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small text-muted mb-1">Cible à atteindre ({{ $indicator->unit }})</label>
                                                        <div class="form-control bg-light">{{ $indicator->target_value }}</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-medium">Nouvelle Valeur Actuelle *</label>
                                                        <input type="number" step="0.01" name="current_value" class="form-control form-control-lg fw-bold text-primary" value="{{ $indicator->current_value }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 mt-4"><i class="bi bi-clock-history text-primary me-2"></i> Suivi des Avancements</h5>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 py-3">Date & Soumetteur</th>
                            <th class="py-3">Avancement Proposé</th>
                            <th class="py-3">Observations / Difficultés</th>
                            <th class="pe-4 py-3 text-end">Statut Validation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activity->progressUpdates as $update)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark">{{ $update->submitted_at ? $update->submitted_at->format('d/m/Y') : $update->created_at->format('d/m/Y') }}</span>
                                    <br><span class="small text-muted">{{ $update->user->name ?? 'Système' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary fs-6">{{ $update->progress_rate }}%</span>
                                </td>
                                <td>
                                    <p class="mb-0 small text-truncate" style="max-width:400px;" title="{{ $update->achievements }}">
                                        {{ $update->achievements ?: 'Aucun commentaire détaillé.' }}
                                    </p>
                                </td>
                                <td class="pe-4 text-end">
                                    @if($update->status == 'validated')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-all me-1"></i> Validé</span>
                                    @elseif($update->status == 'rejected')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i> Rejeté</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-hourglass-split me-1"></i> En attente</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">Aucun historique d'avancement enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Milestone -->
<div class="modal fade" id="createMilestoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('milestones.store') }}">
            @csrf
            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouveau Jalon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Titre du Jalon *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Ex: Fin phase conceptuelle">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Date prévue</label>
                        <input type="date" name="expected_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Statut</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">En attente</option>
                            <option value="achieved">Déjà Atteint</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Create Deliverable -->
<div class="modal fade" id="createDeliverableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('deliverables.store') }}">
            @csrf
            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouveau Livrable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Titre / Nom du Livrable *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Ex: Rapport d'étude">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Description</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Détails du document attendu..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Échéance prévue</label>
                        <input type="date" name="expected_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Statut</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">En attente de livraison</option>
                            <option value="delivered">Déjà Livré</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal Create Indicator -->
<div class="modal fade" id="createIndicatorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('indicators.store') }}">
            @csrf
            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouvel Indicateur de Performance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Titre de l'indicateur *</label>
                        <input type="text" name="title" class="form-control" required placeholder="Ex: Nombre d'écoles construites, Budget dépensé...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Unité de mesure *</label>
                        <input type="text" name="unit" class="form-control" required placeholder="Ex: Écoles, Personnes, $, %...">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Valeur de départ (Baseline)</label>
                            <input type="number" step="0.01" name="baseline" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium text-primary">Valeur Cible (Target) *</label>
                            <input type="number" step="0.01" name="target_value" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info text-white">Créer l'indicateur</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
