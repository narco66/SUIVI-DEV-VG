@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Validation</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Workflows d'Approbation</h2>
                        <p class="text-muted mb-2">Gérez et validez les soumissions d'avancement des activités et plans d'actions.</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">
                            {{ $pendingUpdates->total() }} en attente
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm border-top border-warning border-3 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-hourglass-split text-warning me-2"></i> En attente de validation ({{ $pendingUpdates->total() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Élément concerné</th>
                                    <th class="py-3">Détail soumission</th>
                                    <th class="py-3">Taux proposé</th>
                                    <th class="pe-4 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingUpdates as $update)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold text-dark">
                                                @if($update->updatable_type == 'App\Models\Activity')
                                                    <span class="badge bg-light text-dark border me-1">Activité</span>
                                                @elseif($update->updatable_type == 'App\Models\Action')
                                                    <span class="badge bg-light text-dark border me-1">Action</span>
                                                @else
                                                    <span class="badge bg-light text-dark border me-1">Global</span>
                                                @endif
                                                {{ $update->updatable->title ?? 'Élément introuvable' }}
                                            </div>
                                            <div class="small text-muted mt-1">
                                                Soumis par <strong>{{ $update->user->name ?? 'Système' }}</strong> 
                                                ({{ $update->user->institution->acronym ?? 'CEEAC' }})
                                                le {{ $update->report_date ? $update->report_date->format('d/m/Y') : $update->created_at->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <p class="mb-0 small text-dark fst-italic" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                "{{ $update->comment ?: 'Aucun commentaire.' }}"
                                            </p>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0 fw-bold text-primary me-2">{{ $update->progress_rate }}%</h4>
                                                @if($update->updatable && isset($update->updatable->progress_rate))
                                                    <span class="small text-muted">(Actuel: {{ $update->updatable->progress_rate }}%)</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#validateModal-{{ $update->id }}">
                                                Traiter
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal de Validation -->
                                    <div class="modal fade" id="validateModal-{{ $update->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form method="POST" action="{{ route('validations.store') }}">
                                                @csrf
                                                <input type="hidden" name="update_id" value="{{ $update->id }}">
                                                
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Traitement de l'avancement</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="bg-light p-3 rounded mb-3">
                                                            <p class="small text-muted mb-1">Élément :</p>
                                                            <p class="fw-semibold mb-2">{{ $update->updatable->title ?? 'N/A' }}</p>
                                                            
                                                            <p class="small text-muted mb-1">Nouveau taux demandé :</p>
                                                            <h3 class="fw-bold text-primary mb-2">{{ $update->progress_rate }}%</h3>
                                                            
                                                            <p class="small text-muted mb-1">Observation point focal :</p>
                                                            <p class="mb-0 fst-italic">"{{ $update->comment ?: 'Aucun commentaire fourni.' }}"</p>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">Décision *</label>
                                                            <select name="status" class="form-select border-2" required>
                                                                <option value="approved">Approuver (Actualise le taux)</option>
                                                                <option value="rejected">Rejeter (Demander correction)</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">Commentaire du validateur (Optionnel)</label>
                                                            <textarea name="comment" class="form-control" rows="3" placeholder="Motif du rejet ou observation d'approbation..."></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-medium">Niveau d'approbation</label>
                                                            <input type="number" name="level" class="form-control" value="1" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0 pt-0">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary">Confirmer</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-shield-check d-block mb-3 fs-1 text-light"></i>
                                            Aucune validation en attente. Tout est à jour.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($pendingUpdates->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $pendingUpdates->links() }}
                </div>
                @endif
            </div>

            <!-- Historique des validations -->
            <h5 class="fw-bold mb-3 mt-5"><i class="bi bi-clock-history me-2 text-primary"></i> Mes dernières actions</h5>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4">Date</th>
                                    <th>Élément validé</th>
                                    <th>Taux acté</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentValidations as $validation)
                                    <tr>
                                        <td class="ps-4 small text-muted">{{ $validation->validated_at ? $validation->validated_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td>
                                            <span class="fw-medium text-dark">{{ $validation->progressUpdate->updatable->title ?? 'N/A' }}</span>
                                            <br><small class="text-muted">Par {{ $validation->progressUpdate->user->name ?? 'Inconnu' }}</small>
                                        </td>
                                        <td><span class="fw-bold">{{ $validation->progressUpdate->progress_rate ?? '-' }}%</span></td>
                                        <td>
                                            @if($validation->status == 'approved')
                                                <span class="badge bg-success-subtle text-success"><i class="bi bi-check me-1"></i> Approuvé</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x me-1"></i> Rejeté</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">Aucun historique de validation.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection



