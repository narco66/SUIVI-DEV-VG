@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 max-w-4xl mx-auto">`r`n    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-pencil-square fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Planification</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Modifier le Plan d'Action</h2>
                        <p class="text-muted mb-2">{{ Str::limit($actionPlan->title, 60) }}</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">Édition en cours</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto mt-0 d-flex align-items-center gap-2">
            <form action="{{ route('action-plans.destroy', $actionPlan->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plan d\'action ? Toutes les données associées seront perdues.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger px-3 py-2 shadow-sm" title="Supprimer">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
            <a href="{{ route('action-plans.index') }}" class="btn btn-outline-secondary px-4 py-2 shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Annuler
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
            <form method="POST" action="{{ route('action-plans.update', $actionPlan->id) }}">
                @csrf
                @method('PUT')
                
                <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Paramètres du Plan</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Décision Source *</label>
                        <select name="decision_id" class="form-select" required>
                            @foreach($decisions as $dec)
                                <option value="{{ $dec->id }}" {{ old('decision_id', $actionPlan->decision_id) == $dec->id ? 'selected' : '' }}>
                                    {{ $dec->code }} - {{ Str::limit($dec->title, 100) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-medium">Titre du Plan d'Action *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $actionPlan->title) }}" required>
                    </div>
                </div>

                <div class="row g-4 mb-4 border-top pt-2">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Date de début</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($actionPlan->start_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Date de fin prévue</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($actionPlan->end_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Statut Actuel *</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" {{ old('status', $actionPlan->status) == 'draft' ? 'selected' : '' }}>Brouillon (En conception)</option>
                            <option value="active" {{ old('status', $actionPlan->status) == 'active' ? 'selected' : '' }}>Actif (Validé)</option>
                            <option value="suspended" {{ old('status', $actionPlan->status) == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                            <option value="completed" {{ old('status', $actionPlan->status) == 'completed' ? 'selected' : '' }}>Achevé</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-medium">Description Générale (Optionnel)</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $actionPlan->description) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                    <button type="submit" class="btn btn-primary px-5 fw-medium"><i class="bi bi-save me-2"></i>Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


