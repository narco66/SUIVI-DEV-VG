@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 max-w-4xl mx-auto">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Nouvel Axe Stratégique</h2>
            <p class="text-muted mb-0">Ajout d'un axe au plan d'action</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ $actionPlan ? route('action-plans.show', $actionPlan->id) : route('action-plans.index') }}" class="btn btn-outline-secondary px-4">
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
            <form method="POST" action="{{ route('strategic-axes.store') }}">
                @csrf
                
                <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Détails de l'Axe</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Plan d'action de rattachement *</label>
                        <select name="action_plan_id" class="form-select" required>
                            <option value="">Sélectionner</option>
                            @foreach($actionPlans as $plan)
                                <option value="{{ $plan->id }}" {{ (old('action_plan_id') == $plan->id || ($actionPlan && $actionPlan->id == $plan->id)) ? 'selected' : '' }}>
                                    {{ Str::limit($plan->title, 100) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-medium">Code (Optionnel)</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Ex: AXE-01">
                    </div>

                    <div class="col-md-9">
                        <label class="form-label fw-medium">Titre de l'Axe *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                    <button type="submit" class="btn btn-primary px-5 fw-medium"><i class="bi bi-check2-circle me-2"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
