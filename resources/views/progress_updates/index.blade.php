@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <h2 class="h3 fw-bold text-dark mb-0">Historique des Avancements</h2>
            <p class="text-muted mb-0">Contrôle et validation des rapports remontés</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden fade-in" style="animation-delay: 0.1s;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-borderless">
                <thead class="bg-light text-muted small text-uppercase" style="letter-spacing: 0.03em;">
                    <tr class="border-bottom">
                        <th class="ps-4 py-3 fw-medium">Date</th>
                        <th class="py-3 fw-medium">Cible (Activité / Décision)</th>
                        <th class="py-3 fw-medium">Auteur</th>
                        <th class="py-3 fw-medium">Nouveau Taux</th>
                        <th class="py-3 fw-medium">Statut</th>
                        <th class="pe-4 py-3 text-end fw-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($updates as $update)
                    <tr class="border-bottom border-light hover-bg-light transition-all">
                        <td class="ps-4 text-muted small py-3"><i class="bi bi-clock me-1"></i>{{ $update->submitted_at ? $update->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="py-3">
                            <span class="badge bg-light text-dark border d-inline-flex align-items-center mb-1 py-1 px-2 fw-normal">
                                @if(class_basename($update->updatable_type) == 'Decision') <i class="bi bi-file-earmark-text text-primary me-2"></i> Décision
                                @elseif(class_basename($update->updatable_type) == 'ActionPlan') <i class="bi bi-diagram-3 text-success me-2"></i> Plan d'Action
                                @elseif(class_basename($update->updatable_type) == 'Action') <i class="bi bi-record-circle text-warning me-2"></i> Action
                                @elseif(class_basename($update->updatable_type) == 'Activity') <i class="bi bi-play-circle text-danger me-2"></i> Activité
                                @endif
                            </span>
                            <br>
                            <small class="text-dark fw-medium text-truncate d-inline-block mt-1" style="max-width: 250px;">{{ $update->updatable->title ?? 'N/A' }}</small>
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-2 small" style="width: 28px; height: 28px;">
                                    <b>{{ strtoupper(substr($update->author->name ?? 'S', 0, 1)) }}</b>
                                </div>
                                <span>{{ $update->author->name ?? 'Système' }}</span>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-primary px-3 py-2 rounded-pill fs-6 shadow-sm">{{ $update->progress_rate }}%</span>
                        </td>
                        <td class="py-3">
                            @if($update->status == 'validated')
                                <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-check-circle me-1"></i>Validé</span>
                            @elseif($update->status == 'pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>En attente</span>
                            @elseif($update->status == 'rejected')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>Rejeté</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <div class="d-flex justify-content-end align-items-center">
                                <a href="{{ route('progress-updates.show', $update) }}" class="btn btn-sm btn-light text-primary py-2 px-3 border hover-elevate rounded-pill shadow-sm" title="Voir les details"><i class="bi bi-eye me-1"></i>Détails</a>
                            @if($update->status == 'pending')
                            <div class="btn-group ms-1">
                                <form action="{{ route('validations.store') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="update_id" value="{{ $update->id }}">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Valider" onclick="return confirm('Confirmez-vous la validation de cet avancement ?');"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form action="{{ route('validations.store') }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <input type="hidden" name="update_id" value="{{ $update->id }}">
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Rejeter" onclick="return confirm('Confirmez-vous le rejet de cet avancement ?');"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted bg-light bg-opacity-50">
                            <div class="icon-shape bg-white text-muted rounded-circle icon-lg mx-auto mb-3 shadow-sm border">
                                <i class="bi bi-inbox fs-4"></i>
                            </div>
                            <p class="mb-0">Aucun rapport d'avancement trouvé.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($updates->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $updates->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

