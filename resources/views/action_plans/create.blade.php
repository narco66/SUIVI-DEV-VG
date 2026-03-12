@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 max-w-4xl mx-auto">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Nouveau Plan d'Action</h2>
            <p class="text-muted mb-0">Rattachement à une décision communautaire</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('action-plans.index') }}" class="btn btn-outline-secondary px-4">
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
            <form method="POST" action="{{ route('action-plans.store') }}">
                @csrf
                
                <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Paramètres du Plan</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Décision Source *</label>
                        <select name="decision_id" class="form-select" required>
                            <option value="">Sélectionner la décision de rattachement</option>
                            @foreach($decisions as $dec)
                                <option value="{{ $dec->id }}" {{ (old('decision_id') == $dec->id || $selectedDecision == $dec->id) ? 'selected' : '' }}>
                                    {{ $dec->code }} - {{ Str::limit($dec->title, 100) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-medium">Titre du Plan d'Action *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Ex: Plan d'action pour le déploiement de...">
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
                        <label class="form-label fw-medium">Statut Initial *</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Brouillon (En conception)</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif (Validé)</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-medium">Description Générale (Optionnel)</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Contexte, enjeux, finalité du plan d'action...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                    <button type="reset" class="btn btn-light px-4">Effacer</button>
                    <button type="submit" class="btn btn-primary px-5 fw-medium"><i class="bi bi-check2-circle me-2"></i>Enregistrer le plan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
