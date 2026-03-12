@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 max-w-4xl mx-auto">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Nouvelle Activité</h2>
            <p class="text-muted mb-0">Génération d'une activité opérationnelle</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('activities.store') }}">
                @csrf
                
                <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Informations de l'Activité</h5>
                
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
