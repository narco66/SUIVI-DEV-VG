@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Détails du Plan d'Action</h2>
            <p class="text-muted mb-0">Structure de planification (Axes, Actions, Activités)</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('action-plans.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('action-plans.edit', $actionPlan->id) }}" class="btn btn-primary px-4">
                <i class="bi bi-pencil me-2"></i> Modifier le plan
            </a>
            <button type="button" class="btn btn-dark px-4" data-bs-toggle="modal" data-bs-target="#createAxisModal">
                <i class="bi bi-diagram-2 me-2"></i> Ajouter un Axe Stratégique
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold mb-2">{{ $actionPlan->title }}</h4>
                    <div class="d-flex gap-3 text-muted small">
                        <span><i class="bi bi-link-45deg me-1"></i> Rattaché à : <strong><a href="{{ $actionPlan->decision ? route('decisions.show', $actionPlan->decision_id) : '#' }}" class="text-decoration-none">{{ $actionPlan->decision->code ?? 'N/A' }}</a></strong></span>
                        <span><i class="bi bi-calendar-range me-1"></i> Période : {{ $actionPlan->start_date ? $actionPlan->start_date->format('d/m/Y') : '-' }} au {{ $actionPlan->end_date ? $actionPlan->end_date->format('d/m/Y') : '-' }}</span>
                    </div>
                </div>
                <div>
                    @if($actionPlan->status == 'completed')
                        <span class="badge bg-success-subtle text-success px-3 py-2 fs-6 rounded-pill">Achevé</span>
                    @elseif($actionPlan->status == 'active')
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 fs-6 rounded-pill">Actif</span>
                    @elseif($actionPlan->status == 'suspended')
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 fs-6 rounded-pill">Suspendu</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 fs-6 rounded-pill">Brouillon</span>
                    @endif
                </div>
            </div>

            @if($actionPlan->description)
            <div class="mb-4">
                <h6 class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em;">Contexte & Description</h6>
                <p class="text-dark">{{ $actionPlan->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Structure du  Plan -->
    <h5 class="fw-bold mb-3 mt-4">Axes Stratégiques & Actions</h5>
    
    @forelse($actionPlan->strategicAxes as $index => $axis)
        <div class="card border-0 shadow-sm mb-4 border-start border-primary border-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Axe {{ $index + 1 }} : {{ $axis->title }}</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createActionModal" data-axis-id="{{ $axis->id }}" data-axis-title="{{ $axis->title }}">
                    <i class="bi bi-plus"></i> Ajouter une action
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-4">Code</th>
                                <th>Action / Activité</th>
                                <th>Période</th>
                                <th>Statut</th>
                                <th>Avancement</th>
                                <th class="pe-4 text-end">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($axis->actions as $action)
                                <tr>
                                    <td class="ps-4 fw-medium text-muted">{{ $action->code }}</td>
                                    <td><span class="fw-semibold text-dark">{{ $action->title }}</span></td>
                                    <td class="small text-muted">{{ $action->start_date ? $action->start_date->format('d/m/y') : '-' }}</td>
                                    <td><span class="badge bg-secondary-subtle text-secondary">{{ $action->status }}</span></td>
                                    <td>
                                        <div class="progress" style="height: 6px; width: 60px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $action->progress_rate }}%"></div>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-light border" title="Ajouter une activité" data-bs-toggle="modal" data-bs-target="#createActivityModal" data-action-id="{{ $action->id }}" data-action-title="{{ $action->title }}">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border"><i class="bi bi-three-dots"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Affichage des activités imbriquées -->
                                @foreach($action->activities as $activity)
                                <tr class="bg-light">
                                    <td class="ps-5 text-muted small"><i class="bi bi-arrow-return-right me-2"></i> {{ $activity->code }}</td>
                                    <td><a href="{{ route('activities.show', $activity->id) }}" class="small fw-semibold text-decoration-none">{{ $activity->title }}</a></td>
                                    <td class="small text-muted">{{ $activity->start_date ? $activity->start_date->format('d/m/y') : '-' }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $activity->status }}</span></td>
                                    <td><span class="small fw-medium">{{ $activity->progress_rate }}%</span></td>
                                    <td class="pe-4 text-end"></td>
                                </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted small">Aucune action définie pour cet axe.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm border-dashed text-center py-5">
            <div class="card-body">
                <i class="bi bi-inboxes text-muted fs-1 mb-3 d-block"></i>
                <h6 class="fw-semibold text-muted">Ce plan d'action est vide.</h6>
                <p class="text-muted small mb-0">Commencez par créer des axes stratégiques pour structurer votre plan.</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Modal Create Strategic Axis -->
<div class="modal fade" id="createAxisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('strategic-axes.store') }}">
            @csrf
            <input type="hidden" name="action_plan_id" value="{{ $actionPlan->id }}">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouvel Axe Stratégique</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-medium">Code</label>
                            <input type="text" name="code" class="form-control" placeholder="Ex: AXE-01">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-medium">Titre de l'Axe *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-2"></i>Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Create Action -->
<div class="modal fade" id="createActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('actions.store') }}">
            @csrf
            <input type="hidden" name="strategic_axis_id" id="action_strategic_axis_id" value="">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouvelle Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Axe : <strong id="action_axis_title_display"></strong></p>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-medium">Code</label>
                            <input type="text" name="code" class="form-control" placeholder="Ex: ACT-01">
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-medium">Titre de l'Action *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Date de début</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Date de fin</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-medium">Statut</label>
                            <select name="status" class="form-select" required>
                                <option value="planned">Planifiée</option>
                                <option value="in_progress">En cours</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-medium">Priorité</label>
                            <select name="priority" class="form-select" required>
                                <option value="1">Haute</option>
                                <option value="2" selected>Moy.</option>
                                <option value="3">Basse</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-2"></i>Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Create Activity -->
<div class="modal fade" id="createActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('activities.store') }}">
            @csrf
            <input type="hidden" name="action_id" id="activity_action_id" value="">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouvelle Activité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3">Action : <strong id="activity_action_title_display"></strong></p>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-medium">Code *</label>
                            <input type="text" name="code" class="form-control" value="ACTV-{{ rand(100,999) }}" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label fw-medium">Intitulé de l'activité *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Date de début</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Date de fin</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Statut</label>
                            <select name="status" class="form-select" required>
                                <option value="planned">Planifiée</option>
                                <option value="in_progress">En cours</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Direction en charge</label>
                            <select name="direction_id" class="form-select">
                                <option value="">Sélectionner</option>
                                @foreach($directions as $dir)
                                    <option value="{{ $dir->id }}">{{ $dir->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Budget</label>
                            <input type="number" step="0.01" min="0" name="budget" class="form-control" placeholder="Montant">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-2"></i>Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var createActionModal = document.getElementById('createActionModal');
        if (createActionModal) {
            createActionModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var axisId = button.getAttribute('data-axis-id');
                var axisTitle = button.getAttribute('data-axis-title');
                
                var inputAxisId = createActionModal.querySelector('#action_strategic_axis_id');
                var displayAxisTitle = createActionModal.querySelector('#action_axis_title_display');
                
                inputAxisId.value = axisId;
                displayAxisTitle.textContent = axisTitle;
            });
        }

        var createActivityModal = document.getElementById('createActivityModal');
        if (createActivityModal) {
            createActivityModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var actionId = button.getAttribute('data-action-id');
                var actionTitle = button.getAttribute('data-action-title');
                
                var inputActionId = createActivityModal.querySelector('#activity_action_id');
                var displayActionTitle = createActivityModal.querySelector('#activity_action_title_display');
                
                inputActionId.value = actionId;
                displayActionTitle.textContent = actionTitle;
                
                var codeInput = createActivityModal.querySelector('input[name="code"]');
                codeInput.value = 'ACTV-' + Math.floor(Math.random() * 900 + 100);
            });
        }
    });
</script>
@endsection
