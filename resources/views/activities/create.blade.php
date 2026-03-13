?@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 max-w-4xl mx-auto">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-play-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Exécution</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Nouvelle Activité</h2>
                        <p class="text-muted mb-2">Génération d'une activité opérationnelle</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">Formulaire de saisie</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto mt-0 d-flex align-items-center gap-2">
            <a href="{{ $actionData ? route('action-plans.show', $actionData->strategicAxis->action_plan_id) : route('action-plans.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden border">
        <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('activities.store') }}">
                @csrf
                
                <h5 class="fw-bold mb-4 text-primary border-bottom border-primary-subtle pb-2">Informations de l'Activité</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Action de rattachement *</label>
                        <select name="action_id" class="form-select" required>
                            <option value="">Sélectionner</option>
                            @foreach($actions as $act)
                                <option value="{{ $act->id }}" {{ (old('action_id') == $act->id || ($actionData && $actionData->id == $act->id)) ? 'selected' : '' }}>
                                    [{{ $act->strategicAxis->title ?? 'Axe Inconnu' }}] - {{ $act->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-medium">Code (Unique) *</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', 'ACTV-'.rand(100,999)) }}" required>
                    </div>

                    <div class="col-md-9">
                        <label class="form-label fw-medium">Intitulé de l'activité *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                </div>

                <div class="row g-4 mb-4 border-top pt-2">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Date de début</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Date de fin prévue</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Statut Inicial *</label>
                        <select name="status" class="form-select" required>
                            <option value="planned" {{ old('status') == 'planned' ? 'selected' : '' }}>Planifiée</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours d'exécution</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Direction en charge</label>
                        <select name="direction_id" class="form-select">
                            <option value="">Sélectionner (optionnel)</option>
                            @foreach($directions as $dir)
                                <option value="{{ $dir->id }}" {{ old('direction_id') == $dir->id ? 'selected' : '' }}>{{ $dir->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Budget alloué (Optionnel)</label>
                        <input type="number" step="0.01" min="0" name="budget" class="form-control" value="{{ old('budget') }}" placeholder="Montant en devise">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                    <button type="submit" class="btn btn-primary px-5 fw-medium"><i class="bi bi-check2-circle me-2"></i>Créer Activité</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection





