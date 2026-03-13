@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-journal-check fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Administration</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Journal de Sécurité et de Traçabilité</h2>
                        <p class="text-muted mb-2">Consultez les actions utilisateurs, filtres de recherche et détails d'audit en un seul espace.</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">
                            {{ $logs->total() }} entrée(s) d'audit
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto d-flex align-items-stretch">
            <div class="h-100 w-100 d-flex flex-column justify-content-between rounded-4 border bg-white p-4 shadow-sm">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Journal d'Audit</li>
                    </ol>
                </nav>
                <span class="badge bg-light text-dark border fw-normal">Traçabilité complète</span>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('audit-logs.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-medium text-muted">Utilisateur</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">Tous les utilisateurs</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-medium text-muted">Type d'Action</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">Toutes les actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst($action) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-medium text-muted">Date de début</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-medium text-muted">Date de fin</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100 me-2"><i class="bi bi-filter"></i> Filtrer</button>
                    @if(request()->anyFilled(['user_id', 'action', 'date_from', 'date_to']))
                        <a href="{{ route('audit-logs.index') }}" class="btn btn-light border btn-sm"><i class="bi bi-x"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Date & Heure</th>
                        <th class="py-3">Utilisateur</th>
                        <th class="py-3">Action</th>
                        <th class="py-3">Cible (Entité concernée)</th>
                        <th class="py-3">Adresse IP</th>
                        <th class="pe-4 py-3 text-end">Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-medium text-dark">{{ $log->created_at->format('d/m/Y') }}</span>
                            <div class="small text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2 small fw-bold" style="width: 25px; height: 25px; font-size: 0.7rem;">
                                        {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="small fw-medium">{{ $log->user->name }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Système / Inconnu</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = 'bg-secondary';
                                if (str_contains(strtolower($log->action), 'create') || str_contains(strtolower($log->action), 'ajout')) $badgeClass = 'bg-success';
                                elseif (str_contains(strtolower($log->action), 'update') || str_contains(strtolower($log->action), 'modif')) $badgeClass = 'bg-primary';
                                elseif (str_contains(strtolower($log->action), 'delete') || str_contains(strtolower($log->action), 'suppr')) $badgeClass = 'bg-danger';
                                elseif (str_contains(strtolower($log->action), 'login')) $badgeClass = 'bg-info text-dark';
                            @endphp
                            <span class="badge {{ $badgeClass }} bg-opacity-75">{{ ucfirst($log->action) }}</span>
                        </td>
                        <td>
                            @if($log->auditable_type)
                                <div class="small">
                                    <span class="text-muted">{{ class_basename($log->auditable_type) }}</span>
                                    <span class="fw-medium">#{{ $log->auditable_id }}</span>
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td><span class="badge border border-light text-muted fw-normal font-monospace">{{ $log->ip_address ?: 'N/A' }}</span></td>
                        <td class="pe-4 text-end">
                            <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#viewModal{{ $log->id }}" title="Voir les détails">
                                <i class="bi bi-eye text-primary"></i>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="viewModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-bottom-0 pb-0">
                                    <h5 class="modal-title fw-bold">Détails de l'Action</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="bg-light p-3 rounded h-100">
                                                <h6 class="fw-bold text-muted text-uppercase small mb-3">Contexte</h6>
                                                <ul class="list-unstyled small mb-0">
                                                    <li class="mb-2"><strong>ID:</strong> {{ $log->id }}</li>
                                                    <li class="mb-2"><strong>Date:</strong> {{ $log->created_at->format('d/m/Y H:i:s') }}</li>
                                                    <li class="mb-2"><strong>Utilisateur:</strong> {{ $log->user ? $log->user->name : 'Système' }}</li>
                                                    <li class="mb-2"><strong>Action:</strong> <span class="badge {{ $badgeClass }}">{{ $log->action }}</span></li>
                                                    <li class="mb-2"><strong>Entité:</strong> {{ $log->auditable_type }} #{{ $log->auditable_id }}</li>
                                                    <li><strong>Adresse IP:</strong> {{ $log->ip_address }}</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-muted text-uppercase small mb-3">Modifications des Valeurs</h6>

                                            @if($log->old_values || $log->new_values)
                                                <div class="accordion accordion-flush bg-light rounded" id="accordion{{ $log->id }}">
                                                    @if($log->old_values && count($log->old_values) > 0)
                                                    <div class="accordion-item bg-transparent border-0">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOld{{ $log->id }}">
                                                                <span class="text-danger fw-medium"><i class="bi bi-dash-circle me-2"></i>Anciennes valeurs</span>
                                                            </button>
                                                        </h2>
                                                        <div id="flush-collapseOld{{ $log->id }}" class="accordion-collapse collapse" data-bs-parent="#accordion{{ $log->id }}">
                                                            <div class="accordion-body pt-0 pb-3">
                                                                <pre class="bg-white p-2 rounded small text-danger font-monospace border">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if($log->new_values && count($log->new_values) > 0)
                                                    <div class="accordion-item bg-transparent border-0 border-top mt-1 pt-1">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNew{{ $log->id }}">
                                                                <span class="text-success fw-medium"><i class="bi bi-plus-circle me-2"></i>Nouvelles valeurs</span>
                                                            </button>
                                                        </h2>
                                                        <div id="flush-collapseNew{{ $log->id }}" class="accordion-collapse collapse" data-bs-parent="#accordion{{ $log->id }}">
                                                            <div class="accordion-body pt-0 pb-3">
                                                                <pre class="bg-white p-2 rounded small text-success font-monospace border">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="alert alert-light text-center small text-muted border">
                                                    <i class="bi bi-info-circle d-block fs-3 mb-2"></i>
                                                    Aucun détail de modification enregistré pour cette action.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top-0 pt-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-3 text-light"></i>
                            Aucun journal d'audit ne correspond à vos critères.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection