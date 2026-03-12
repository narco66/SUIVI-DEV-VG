@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 max-w-4xl mx-auto">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Nouvelle Décision</h2>
            <p class="text-muted mb-0">Enregistrement d'un acte communautaire</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('decisions.index') }}" class="btn btn-outline-secondary px-4">
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
            <form method="POST" action="{{ route('decisions.store') }}">
                @csrf
                
                <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Informations Générales</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Référence / Code *</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Ex: DEC-2026-001">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label fw-medium">Titre de la décision *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Type d'acte *</label>
                        <select name="type_id" class="form-select" required>
                            <option value="">Sélectionner</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Session rattachée</label>
                        <select name="session_id" class="form-select">
                            <option value="">Indépendante / Hors Session</option>
                            @foreach($sessions as $sess)
                                <option value="{{ $sess->id }}" {{ old('session_id') == $sess->id ? 'selected' : '' }}>{{ $sess->code }} - {{ $sess->type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Institution Chef de File</label>
                        <select name="institution_id" class="form-select">
                            <option value="">Sélectionner</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ old('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row g-4 mb-4 border-top pt-2">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Date d'adoption *</label>
                        <input type="date" name="date_adoption" class="form-control" value="{{ old('date_adoption') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Date d'échéance (Cible)</label>
                        <input type="date" name="date_echeance" class="form-control" value="{{ old('date_echeance') }}">
                    </div>
                </div>

                <div class="row g-4 mb-5 border-top pt-2">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Priorité *</label>
                        <select name="priority" class="form-select" required>
                            <option value="3" {{ old('priority') == 3 ? 'selected' : '' }}>Moyenne</option>
                            <option value="2" {{ old('priority') == 2 ? 'selected' : '' }}>Haute</option>
                            <option value="1" {{ old('priority') == 1 ? 'selected' : '' }}>Critique</option>
                            <option value="4" {{ old('priority') == 4 ? 'selected' : '' }}>Basse</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Statut Initial *</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active / A planifier</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En exécution</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-medium">Résumé de la décision (Optionnel)</label>
                        <textarea name="summary" class="form-control" rows="3">{{ old('summary') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                    <button type="reset" class="btn btn-light px-4">Effacer</button>
                    <button type="submit" class="btn btn-primary px-5 fw-medium"><i class="bi bi-check2-circle me-2"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
