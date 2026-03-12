@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Detail du rapport d'avancement</h2>
            <p class="text-muted mb-0">Reference #{{ $progressUpdate->id }}</p>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('progress-updates.index') }}" class="btn btn-outline-secondary px-4 rounded-pill shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </a>
            <a href="{{ route('progress-updates.export-pdf', $progressUpdate->id) }}" class="btn btn-danger px-4 text-white rounded-pill shadow-sm">
                <i class="bi bi-file-earmark-pdf me-2"></i> Exporter PDF
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-0">
                    <div class="bg-light bg-opacity-50 p-4 p-md-5 border-bottom border-primary border-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill mb-2 shadow-sm"><i class="bi bi-card-checklist me-1"></i> {{ class_basename($progressUpdate->updatable_type) }}</span>
                            <h4 class="fw-bold text-dark mt-2 mb-0" style="line-height: 1.4;">{{ $progressUpdate->updatable->title ?? 'Element non disponible' }}</h4>
                        </div>
                        <div class="ms-4 text-center">
                            <div class="badge bg-white text-primary border border-primary fs-4 px-4 py-3 rounded-pill shadow-sm">
                                <i class="bi bi-graph-up-arrow me-2"></i>{{ $progressUpdate->progress_rate }}%
                            </div>
                        </div>
                    </div>

                    <div class="p-4 p-md-5">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 h-100 border">
                                    <h6 class="text-uppercase fw-bold text-muted mb-3 d-flex align-items-center" style="font-size: 0.85rem; letter-spacing: 0.05em;"><i class="bi bi-trophy text-success fs-5 me-2"></i> Realisations</h6>
                                    <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $progressUpdate->achievements ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 h-100 border">
                                    <h6 class="text-uppercase fw-bold text-muted mb-3 d-flex align-items-center" style="font-size: 0.85rem; letter-spacing: 0.05em;"><i class="bi bi-exclamation-triangle text-danger fs-5 me-2"></i> Difficultes</h6>
                                    <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $progressUpdate->difficulties ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 bg-light rounded-4 h-100 border">
                                    <h6 class="text-uppercase fw-bold text-muted mb-3 d-flex align-items-center" style="font-size: 0.85rem; letter-spacing: 0.05em;"><i class="bi bi-signpost-split text-info fs-5 me-2"></i> Prochaines etapes</h6>
                                    <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $progressUpdate->next_steps ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-4 bg-light text-muted rounded-4 h-100 border">
                                    <h6 class="text-uppercase fw-bold text-muted mb-3 d-flex align-items-center" style="font-size: 0.85rem; letter-spacing: 0.05em;"><i class="bi bi-life-preserver text-warning fs-5 me-2"></i> Besoins d'appui</h6>
                                    <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $progressUpdate->support_needs ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm mb-4 rounded-4 hover-elevate">
                <div class="card-body p-4">
                    <h6 class="text-uppercase fw-bold text-muted mb-4 pb-2 border-bottom" style="font-size: 0.8rem; letter-spacing: 0.05em;"><i class="bi bi-info-circle text-primary me-2"></i> Informations générales</h6>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-person fs-5"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0 fw-medium">Auteur</p>
                            <p class="fw-semibold text-dark mb-0 fs-6">{{ $progressUpdate->author->name ?? 'Systeme' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-clock fs-5"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0 fw-medium">Date de soumission</p>
                            <p class="fw-semibold text-dark mb-0 fs-6">{{ $progressUpdate->submitted_at ? $progressUpdate->submitted_at->format('d/m/Y H:i') : '-' }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="bi bi-flag fs-5"></i>
                        </div>
                        <div>
                            <p class="text-muted small mb-0 fw-medium">Statut</p>
                            <div class="mt-1">
                                @if($progressUpdate->status === 'validated')
                                    <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-check-circle me-1"></i>Valide</span>
                                @elseif($progressUpdate->status === 'rejected')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>Rejete</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>En attente</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase fw-bold text-muted mb-4 pb-2 border-bottom" style="font-size: 0.8rem; letter-spacing: 0.05em;"><i class="bi bi-shield-check text-primary me-2"></i> Historique des validations</h6>
                    @forelse($progressUpdate->validations as $validation)
                        <div class="bg-light rounded-4 p-4 border mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Niveau {{ $validation->level }}</span>
                                <small class="text-muted fw-medium"><i class="bi bi-calendar3 me-1"></i>{{ $validation->validated_at ? $validation->validated_at->format('d/m/Y H:i') : '-' }}</small>
                            </div>
                            <p class="fw-bold text-dark mb-1 d-flex align-items-center"><i class="bi bi-person-fill text-muted me-2"></i>{{ $validation->validator->name ?? 'Validateur' }}</p>
                            <p class="text-muted small mb-3 fst-italic">"{{ $validation->comment ?? 'Aucun commentaire.' }}"</p>
                            <span class="badge {{ $validation->status === 'approved' ? 'bg-success text-white' : 'bg-danger text-white' }} px-3 py-2 rounded-pill shadow-sm w-100 text-center">
                                <i class="bi {{ $validation->status === 'approved' ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i> {{ $validation->status === 'approved' ? 'Approuve' : 'Rejete' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 bg-light rounded-4 border text-muted">
                            <div class="icon-shape bg-white text-muted rounded-circle icon-lg mx-auto mb-3 shadow-sm">
                                <i class="bi bi-inbox fs-4"></i>
                            </div>
                            <p class="mb-0 small">Aucune validation enregistree pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
