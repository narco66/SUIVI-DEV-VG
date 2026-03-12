@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-auto me-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sessions</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">Sessions Institutionnelles</h2>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg me-2"></i> Nouvelle Session
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Code Session</th>
                        <th class="py-3">Organe Responsable</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Lieu / Période</th>
                        <th class="py-3">Statut</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">{{ $session->code }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-bank text-muted me-2"></i>
                                {{ $session->organ ? $session->organ->name : 'N/A' }}
                            </div>
                        </td>
                        <td>
                            @if($session->type === 'ordinaire')
                                <span class="badge bg-primary">Ordinaire</span>
                            @else
                                <span class="badge bg-warning text-dark">Extraordinaire</span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <div><i class="bi bi-geo-alt me-1 text-muted"></i> {{ $session->location ?: 'Non défini' }}</div>
                                <div><i class="bi bi-calendar me-1 text-muted"></i> {{ $session->date_start ? $session->date_start->format('d/m/Y') : '-' }} au {{ $session->date_end ? $session->date_end->format('d/m/Y') : '-' }}</div>
                            </div>
                        </td>
                        <td>
                            @switch($session->status)
                                @case('planned')
                                    <span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-clock me-1"></i> Planifiée</span>
                                    @break
                                @case('ongoing')
                                    <span class="badge bg-primary-subtle text-primary"><i class="bi bi-play-circle me-1"></i> En cours</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i> Clôturée</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x-circle me-1"></i> Annulée</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark">{{ $session->status }}</span>
                            @endswitch
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{ $session->id }}" title="Modifier">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('sessions.destroy', $session->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette session ?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $session->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form method="POST" action="{{ route('sessions.update', $session->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Modifier la Session</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-medium">Code Session *</label>
                                                <input type="text" name="code" class="form-control" value="{{ $session->code }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-medium">Organe *</label>
                                                <select name="organ_id" class="form-select" required>
                                                    <option value="">Sélectionner...</option>
                                                    @foreach($organs as $organ)
                                                        <option value="{{ $organ->id }}" {{ $session->organ_id == $organ->id ? 'selected' : '' }}>{{ $organ->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-medium">Type *</label>
                                                <select name="type" class="form-select" required>
                                                    <option value="ordinaire" {{ $session->type == 'ordinaire' ? 'selected' : '' }}>Ordinaire</option>
                                                    <option value="extraordinaire" {{ $session->type == 'extraordinaire' ? 'selected' : '' }}>Extraordinaire</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-medium">Statut *</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="planned" {{ $session->status == 'planned' ? 'selected' : '' }}>Planifiée</option>
                                                    <option value="ongoing" {{ $session->status == 'ongoing' ? 'selected' : '' }}>En cours</option>
                                                    <option value="completed" {{ $session->status == 'completed' ? 'selected' : '' }}>Clôturée</option>
                                                    <option value="cancelled" {{ $session->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-medium">Date de début</label>
                                                <input type="date" name="date_start" class="form-control" value="{{ $session->date_start ? $session->date_start->format('Y-m-d') : '' }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-medium">Date de fin</label>
                                                <input type="date" name="date_end" class="form-control" value="{{ $session->date_end ? $session->date_end->format('Y-m-d') : '' }}">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label class="form-label fw-medium">Lieu (Ville, Pays)</label>
                                                <input type="text" name="location" class="form-control" value="{{ $session->location }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top-0 pt-0">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-event fs-1 d-block mb-3 text-light"></i>
                            Aucune session institutionnelle configurée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sessions->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('sessions.store') }}">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouvelle Session Institutionnelle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Code Session *</label>
                            <input type="text" name="code" class="form-control" required placeholder="Ex: CCEG-23 (23e Conférence)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Organe Responsable *</label>
                            <select name="organ_id" class="form-select" required>
                                <option value="">Sélectionner...</option>
                                @foreach($organs as $organ)
                                    <option value="{{ $organ->id }}">{{ $organ->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="ordinaire" selected>Ordinaire</option>
                                <option value="extraordinaire">Extraordinaire</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Statut de la session *</label>
                            <select name="status" class="form-select" required>
                                <option value="planned" selected>Planifiée (A venir)</option>
                                <option value="ongoing">En cours</option>
                                <option value="completed">Clôturée</option>
                                <option value="cancelled">Annulée</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Date de début prévue</label>
                            <input type="date" name="date_start" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Date de fin prévue</label>
                            <input type="date" name="date_end" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-medium">Lieu (Ville, Pays, Bâtiment)</label>
                            <input type="text" name="location" class="form-control" placeholder="Ex: Libreville, Siège de la CEEAC...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
