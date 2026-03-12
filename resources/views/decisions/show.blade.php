@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Détails de la Décision</h2>
            <p class="text-muted mb-0 text-uppercase" style="letter-spacing: 0.05em; font-size: 0.85rem;">{{ $decision->code }}</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('decisions.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('decisions.edit', $decision->id) }}" class="btn btn-primary px-4">
                <i class="bi bi-pencil me-2"></i> Modifier
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <!-- Hero Header Block -->
                    <div class="bg-light bg-opacity-50 p-4 p-md-5 border-bottom border-primary border-4 align-items-center">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill mb-2 shadow-sm"><i class="bi bi-tag-fill me-1"></i> {{ $decision->type->name ?? 'Acte non typé' }}</span>
                                <h3 class="fw-bold text-dark mt-2 mb-3" style="line-height: 1.4;">{{ $decision->title }}</h3>
                                <div class="d-flex gap-4 text-muted small fw-medium">
                                    <span><i class="bi bi-calendar-event text-primary me-1"></i> Adoptée le {{ $decision->date_adoption ? $decision->date_adoption->format('d/m/Y') : 'N/A' }}</span>
                                    <span><i class="bi bi-bank text-primary me-1"></i> {{ $decision->institution->name ?? 'CEEAC' }}</span>
                                    <span><i class="bi bi-journal-bookmark text-primary me-1"></i> Session : {{ $decision->session->code ?? 'Indépendante' }}</span>
                                </div>
                            </div>
                            <div class="text-end ms-3">
                                @if($decision->status == 'completed')
                                    <span class="badge bg-success text-white px-3 py-2 fs-6 rounded-pill shadow-sm"><i class="bi bi-check-circle me-1"></i>Achevée</span>
                                @elseif($decision->status == 'in_progress')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fs-6 rounded-pill"><i class="bi bi-arrow-repeat me-1"></i>En exécution</span>
                                @elseif($decision->status == 'delayed')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 fs-6 rounded-pill"><i class="bi bi-exclamation-circle me-1"></i>En retard</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 fs-6 rounded-pill">{{ ucfirst($decision->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-4 p-md-5">
                        <div class="mb-5">
                            <h6 class="text-uppercase fw-bold text-muted mb-3 d-flex align-items-center" style="font-size: 0.8rem; letter-spacing: 0.05em;"><i class="bi bi-card-text text-primary me-2"></i> Résumé & Contexte</h6>
                            <p class="text-dark" style="line-height: 1.6;">{{ $decision->summary ?: 'Aucun résumé défini pour cette décision. Les détails se trouvent dans le document source.' }}</p>
                        </div>

                        <div class="row g-4 bg-light rounded-4 p-4 mx-0 mb-5 border">
                            <div class="col-md-4">
                                <p class="text-muted small mb-1 text-uppercase fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i> Priorité</p>
                                <p class="fw-bold mb-0 fs-5">
                                    @if($decision->priority == 1) <span class="text-danger">Critique</span>
                                    @elseif($decision->priority == 2) <span class="text-warning">Haute</span>
                                    @else <span class="text-secondary">Normale</span> @endif
                                </p>
                            </div>
                            <div class="col-md-4 border-start border-light-subtle">
                                <p class="text-muted small mb-1 text-uppercase fw-semibold"><i class="bi bi-flag me-1"></i> Échéance</p>
                                <p class="fw-bold mb-0 fs-5 {{ $decision->date_echeance && $decision->date_echeance < now() ? 'text-danger' : 'text-dark' }}">
                                    {{ $decision->date_echeance ? $decision->date_echeance->format('d/m/Y') : 'Non définie' }}
                                </p>
                            </div>
                            <div class="col-md-4 border-start border-light-subtle">
                                <p class="text-muted small mb-1 text-uppercase fw-semibold"><i class="bi bi-person me-1"></i> Créateur</p>
                                <p class="fw-bold mb-0 fs-5 text-dark text-truncate" title="{{ $decision->creator->name ?? 'Système' }}">{{ $decision->creator->name ?? 'Système' }}</p>
                            </div>
                        </div>

                    <!-- Acteurs et Responsabilités -->
                    <div class="mt-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0"><i class="bi bi-people-fill text-primary me-2"></i> Acteurs & Responsabilités</h5>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignActorModal">
                                <i class="bi bi-person-plus me-1"></i> Assigner
                            </button>
                        </div>
                        
                        <div class="row g-3">
                            @forelse($decision->actorAssignments as $assignment)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card border border-light-subtle shadow-sm h-100 rounded-4 hover-elevate transition-all">
                                        <div class="card-body p-3 d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                        <span class="fw-bold small">{{ strtoupper(substr($assignment->user->name ?? 'U', 0, 1)) }}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark fs-6">{{ $assignment->user->name ?? 'Utilisateur inconnu' }}</h6>
                                                    </div>
                                                </div>
                                                <p class="text-muted small mb-0 ms-1">{{ $assignment->user->email ?? '' }}</p>
                                                <div class="mt-3 ms-1">
                                                    @if($assignment->type == 'responsable')
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"><i class="bi bi-star-fill me-1"></i>Responsable Principal</span>
                                                    @elseif($assignment->type == 'point_focal')
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1"><i class="bi bi-geo-alt-fill me-1"></i>Point Focal</span>
                                                    @elseif($assignment->type == 'validateur')
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1"><i class="bi bi-shield-check me-1"></i>Validateur</span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">{{ ucfirst($assignment->type) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <form action="{{ route('actor-assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Retirer cet acteur de la décision ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger bg-danger-subtle rounded-circle p-1 ms-2" title="Retirer" style="width: 28px; height: 28px; line-height: 1;"><i class="bi bi-x"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light border text-center text-muted mb-0 rounded-4 p-4">
                                        <div class="icon-shape bg-light text-muted rounded-circle icon-lg mx-auto mb-3">
                                            <i class="bi bi-person-x fs-4"></i>
                                        </div>
                                        Aucun acteur spécifiquement assigné à cette décision pour le moment.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm mb-4 rounded-4 hover-elevate">
                <div class="card-body p-4">
                    <h6 class="text-uppercase fw-bold text-muted mb-4" style="font-size: 0.8rem; letter-spacing: 0.05em;"><i class="bi bi-speedometer text-primary me-2"></i> État d'avancement global</h6>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="display-5 fw-bold text-dark mb-0">{{ config('app.locale') == 'fr' ? number_format($decision->progress_rate, 0) : $decision->progress_rate }}%</h2>
                        <span class="badge {{ $decision->progress_rate == 100 ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} p-2 rounded-circle fs-5"><i class="bi {{ $decision->progress_rate == 100 ? 'bi-star-fill' : 'bi-graph-up-arrow' }}"></i></span>
                    </div>
                    <div class="progress mb-4 bg-light" style="height: 12px; border-radius: 6px;">
                        <div class="progress-bar {{ $decision->progress_rate == 100 ? 'bg-success' : 'bg-primary' }} progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $decision->progress_rate }}%" aria-valuenow="{{ $decision->progress_rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <a href="{{ route('progress-updates.create', ['type' => 'decision', 'id' => $decision->id]) }}" class="btn btn-primary w-100 fw-medium shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i> Mettre à jour l'avancement
                    </a>
                </div>
            </div>

            @include('components.documents', [
                'documents' => $decision->documents,
                'modelClass' => \App\Models\Decision::class,
                'modelId' => $decision->id
            ])
        </div>
    </div>
    
    <!-- Section Plans d'Action -->
    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden border">
                <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-bottom border-light">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark"><i class="bi bi-diagram-3 text-primary me-2"></i> Plans d'Action Rattachés</h5>
                        <p class="small text-muted mb-0">Déclinaisons opérationnelles et feuille de route de cette décision.</p>
                    </div>
                    <a href="{{ route('action-plans.create', ['decision_id' => $decision->id]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Nouveau Plan d'Action
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-borderless">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr class="border-bottom">
                                    <th class="ps-4 py-3 fw-medium">Titre du Plan d'Action</th>
                                    <th class="py-3">Période d'exécution</th>
                                    <th class="py-3">Statut</th>
                                    <th class="pe-4 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($decision->actionPlans as $plan)
                                    <tr class="border-bottom border-light hover-bg-light transition-all">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center py-2">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                                    <i class="bi bi-list-task fs-5"></i>
                                                </div>
                                                <div>
                                                    <a href="{{ route('action-plans.show', $plan->id) }}" class="fw-bold text-dark text-decoration-none d-block fs-6 mb-1">{{ $plan->title }}</a>
                                                    <span class="badge bg-light text-secondary border fw-normal">{{ $plan->strategicAxes()->count() }} axe(s) stratégique(s) défini(s)</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="small fw-medium text-dark bg-light px-2 py-1 rounded">
                                                    @if($plan->start_date && $plan->end_date)
                                                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                                                        {{ $plan->start_date->format('d/m/Y') }} <i class="bi bi-arrow-right text-muted mx-1"></i> {{ $plan->end_date->format('d/m/Y') }}
                                                    @else
                                                        <span class="text-muted fst-italic"><i class="bi bi-calendar me-1"></i> Non planifié</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($plan->status == 'completed')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-check-circle me-1"></i>Achevé</span>
                                            @elseif($plan->status == 'active')
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2"><i class="bi bi-play-circle me-1"></i>Actif</span>
                                            @elseif($plan->status == 'suspended')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2"><i class="bi bi-pause-circle me-1"></i>Suspendu</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-2"><i class="bi bi-pencil me-1"></i>Brouillon</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="{{ route('action-plans.show', $plan->id) }}" class="btn btn-sm btn-light border text-primary rounded-pill px-3 hover-elevate shadow-sm">
                                                Accéder au plan <i class="bi bi-arrow-right-short"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted bg-light bg-opacity-50">
                                            <div class="icon-shape bg-white text-muted rounded-circle icon-lg mx-auto mb-3 shadow-sm border">
                                                <i class="bi bi-inboxes fs-4"></i>
                                            </div>
                                            <p class="mb-0">Aucun plan d'action n'a encore été rattaché à cette décision.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Assigner Acteur -->
<div class="modal fade" id="assignActorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('actor-assignments.store') }}">
            @csrf
            <input type="hidden" name="assignable_type" value="App\Models\Decision">
            <input type="hidden" name="assignable_id" value="{{ $decision->id }}">
            
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Assigner un Acteur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Utilisateur *</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Sélectionner un utilisateur...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Rôle / Type d'assignation *</label>
                        <select name="type" class="form-select" required>
                            <option value="responsable">Responsable Principal</option>
                            <option value="point_focal">Point Focal</option>
                            <option value="validateur">Validateur</option>
                            <option value="collaborateur">Collaborateur</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Assigner</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
