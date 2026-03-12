@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-auto me-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Départements</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">Départements Internes</h2>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg me-2"></i> Nouveau Département
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
                        <th class="ps-4 py-3">Nom du Département</th>
                        <th class="py-3">Code</th>
                        <th class="py-3">Institution/Organe de rattachement</th>
                        <th class="py-3">Commissaire / Responsable</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departments as $dept)
                    <tr>
                        <td class="ps-4 fw-medium text-dark"><i class="bi bi-diagram-2 text-primary me-2"></i> {{ $dept->name }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $dept->code }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building text-muted me-2"></i>
                                {{ $dept->institution ? $dept->institution->name : 'N/A' }}
                            </div>
                        </td>
                        <td>
                            @if($dept->commissaire)
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 small fw-bold" style="width: 25px; height: 25px; font-size: 0.7rem;">
                                        {{ strtoupper(substr($dept->commissaire->name, 0, 1)) }}
                                    </div>
                                    <span class="small">{{ $dept->commissaire->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Non assigné</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{ $dept->id }}" title="Modifier">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?');" class="d-inline">
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
                    <div class="modal fade" id="editModal{{ $dept->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('departments.update', $dept->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Modifier le Département</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Nom du Département *</label>
                                            <input type="text" name="name" class="form-control" value="{{ $dept->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Code usuel *</label>
                                            <input type="text" name="code" class="form-control" value="{{ $dept->code }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Institution de rattachement *</label>
                                            <select name="institution_id" class="form-select" required>
                                                <option value="">Sélectionner...</option>
                                                @foreach($institutions as $inst)
                                                    <option value="{{ $inst->id }}" {{ $dept->institution_id == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Acteur Responsable (Commissaire)</label>
                                            <select name="commissaire_id" class="form-select">
                                                <option value="">Aucun assigné</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $dept->commissaire_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
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
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-diagram-2 fs-1 d-block mb-3 text-light"></i>
                            Aucun département configuré.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($departments->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $departments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('departments.store') }}">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouveau Département</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom du Département *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: Département des Affaires Politiques">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Code usuel *</label>
                        <input type="text" name="code" class="form-control" required placeholder="DAP, DEPS...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Institution de rattachement *</label>
                        <select name="institution_id" class="form-select" required>
                            <option value="">Sélectionner...</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}">{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Acteur Responsable (Commissaire)</label>
                        <select name="commissaire_id" class="form-select">
                            <option value="">Aucun assigné</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
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
