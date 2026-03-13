@extends('layouts.app')

@section('title', 'Validation documentaire')

@section('content')
<div class="container-fluid p-0">

    {{-- En-tête --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                        <i class="bi bi-patch-check fs-5"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-0" style="font-size:.72rem;letter-spacing:.08em;">GED — Workflow documentaire</p>
                        <h2 class="h4 fw-bold mb-0">Validation documentaire multi-niveaux</h2>
                        <p class="text-muted small mb-0">Étapes en attente selon vos rôles et permissions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tableau --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold"><i class="bi bi-list-check me-1 text-primary"></i> Étapes en attente</h6>
            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                {{ $pendingSteps->total() }} en attente
            </span>
        </div>

        @if($pendingSteps->isEmpty())
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-check-circle fs-2 d-block mb-2 text-success opacity-75"></i>
                Aucune validation en attente pour vos rôles.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="bg-light text-muted text-uppercase small">
                        <tr>
                            <th class="ps-4">Document</th>
                            <th>Catégorie</th>
                            <th>Décision</th>
                            <th class="text-center">Niveau</th>
                            <th>Rôle attendu</th>
                            <th class="text-center">Statut</th>
                            <th>En attente depuis</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingSteps as $step)
                            @php $doc = $step->flow->document; @endphp
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('ged.show', $doc) }}" class="fw-semibold text-dark text-decoration-none">
                                        {{ Str::limit($doc->title, 55) }}
                                    </a>
                                    <div class="text-muted small">{{ $doc->reference ?: '' }}</div>
                                </td>
                                <td class="small text-muted">{{ $doc->categoryRef?->name ?? '—' }}</td>
                                <td class="small">
                                    @if($doc->decision)
                                        <a href="{{ route('decisions.show', $doc->decision) }}" class="text-decoration-none text-muted">
                                            {{ $doc->decision->code }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary">N{{ $step->level }}</span>
                                </td>
                                <td class="small">
                                    <span class="badge bg-light text-dark border">{{ $step->validator_role ?? 'Utilisateur assigné' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">En attente</span>
                                </td>
                                <td class="small text-muted">{{ $step->created_at->diffForHumans() }}</td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        {{-- Bouton Approuver → ouvre modal --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalApprove{{ $step->id }}"
                                                title="Approuver">
                                            <i class="bi bi-check-lg me-1"></i> Approuver
                                        </button>
                                        {{-- Bouton Rejeter → ouvre modal --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalReject{{ $step->id }}"
                                                title="Rejeter">
                                            <i class="bi bi-x-lg me-1"></i> Rejeter
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white py-3">
                {{ $pendingSteps->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Modals Approuver / Rejeter par étape --}}
@foreach($pendingSteps as $step)
    @php $doc = $step->flow->document; @endphp

    {{-- Modal Approuver --}}
    <div class="modal fade" id="modalApprove{{ $step->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('ged.validations.approve', $step) }}">
                    @csrf
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold text-success">
                            <i class="bi bi-check-circle me-2"></i>Approuver — Niveau {{ $step->level }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border mb-3">
                            <strong>Document :</strong> {{ Str::limit($doc->title, 80) }}<br>
                            <strong>Rôle attendu :</strong> {{ $step->validator_role ?? 'Utilisateur assigné' }}
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Commentaire <span class="text-muted fw-normal">(optionnel)</span></label>
                            <textarea name="comment" class="form-control" rows="3"
                                      placeholder="Observations, remarques pour la suite..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i> Confirmer l'approbation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Rejeter --}}
    <div class="modal fade" id="modalReject{{ $step->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('ged.validations.reject', $step) }}">
                    @csrf
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="bi bi-x-circle me-2"></i>Rejeter — Niveau {{ $step->level }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border mb-3">
                            <strong>Document :</strong> {{ Str::limit($doc->title, 80) }}<br>
                            <strong>Rôle attendu :</strong> {{ $step->validator_role ?? 'Utilisateur assigné' }}
                        </div>
                        <div class="alert alert-warning small mb-3">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Le rejet mettra fin au circuit de validation. Le document sera retourné à l'état <strong>Rejeté</strong>.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Motif du rejet <span class="text-danger">*</span></label>
                            <textarea name="comment" class="form-control" rows="3"
                                      placeholder="Indiquez clairement le motif du rejet..." required></textarea>
                            <div class="form-text">Ce motif sera consigné dans le journal d'audit.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-lg me-1"></i> Confirmer le rejet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection
