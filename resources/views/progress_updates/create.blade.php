@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 max-w-4xl mx-auto">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Déclarer un Avancement</h2>
            <p class="text-muted mb-0">
                Sur : 
                @if($modelClass == \App\Models\Decision::class) Décision Communautaire
                @elseif($modelClass == \App\Models\ActionPlan::class) Plan d'Action
                @elseif($modelClass == \App\Models\Action::class) Action
                @elseif($modelClass == \App\Models\Activity::class) Activité
                @endif
                - <strong>{{ Str::limit($model->title, 50) }}</strong>
            </p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
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

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('progress-updates.store') }}">
                        @csrf
                        <input type="hidden" name="updatable_type" value="{{ $modelClass }}">
                        <input type="hidden" name="updatable_id" value="{{ $model->id }}">
                        
                        <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">Rapport d'exécution</h5>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nouveau Taux d'Avancement (%) *</label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="range" class="form-range flex-grow-1" min="0" max="100" step="1" id="progressRange" value="{{ old('progress_rate', $model->progress_rate ?? 0) }}" oninput="document.getElementById('progressOutput').value = this.value">
                                    <div class="input-group" style="width: 100px;">
                                        <input type="number" name="progress_rate" id="progressOutput" class="form-control text-center fw-bold text-primary" value="{{ old('progress_rate', $model->progress_rate ?? 0) }}" min="0" max="100" oninput="document.getElementById('progressRange').value = this.value" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4 border-top pt-2">
                            <div class="col-12">
                                <label class="form-label fw-medium text-success"><i class="bi bi-check-circle me-1"></i> Réalisations Majeures *</label>
                                <textarea name="achievements" class="form-control" rows="3" required placeholder="Décrivez les résultats concrets obtenus à ce stade...">{{ old('achievements') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Difficultés Rencontrées (Optionnel)</label>
                                <textarea name="difficulties" class="form-control" rows="2" placeholder="Obstacles, blocages techniques ou institutionnels...">{{ old('difficulties') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-primary"><i class="bi bi-arrow-right-circle me-1"></i> Prochaines Étapes (Optionnel)</label>
                                <textarea name="next_steps" class="form-control" rows="2" placeholder="Actions prévues pour la prochaine période...">{{ old('next_steps') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-warning text-dark"><i class="bi bi-life-preserver me-1"></i> Besoins d'Appui (Optionnel)</label>
                                <textarea name="support_needs" class="form-control" rows="2" placeholder="Ressources humaines, financières, arbitrages nécessaires...">{{ old('support_needs') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                            <button type="submit" class="btn btn-primary px-5 fw-medium"><i class="bi bi-send-check me-2"></i>Soumettre le rapport</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="text-uppercase fw-bold text-muted mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em;"><i class="bi bi-info-circle me-2"></i>Rappel des objectifs</h6>
                    <h5 class="fw-bold mb-3 fs-6">{{ $model->title }}</h5>
                    <p class="small text-muted">{{ Str::limit($model->description ?? $model->summary, 150) }}</p>
                    
                    <hr class="my-3 text-secondary">
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Dernier Avancement</span>
                        <span class="fw-bold">{{ $model->progress_rate ?? 0 }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $model->progress_rate ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
