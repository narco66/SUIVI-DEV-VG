@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-auto me-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Directions</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">Directions Dédiées</h2>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg me-2"></i> Nouvelle Direction
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
                        <th class="ps-4 py-3">Nom de la Direction</th>
                        <th class="py-3">Code</th>
                        <th class="py-3">Département de rattachement</th>
                        <th class="py-3">Directeur / Chef</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($directions as $direction)
                    <tr>
                        <td class="ps-4 fw-medium text-dark"><i class="bi bi-folder-fill text-primary me-2"></i> {{ $direction->name }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $direction->code }}</span></td>
                        <td>
                            <div class="small">
                                <i class="bi bi-diagram-2 text-muted me-1"></i>
                                {{ $direction->department ? $direction->department->name : 'N/A' }}
                            </div>
                        </td>
                        <td>
                            @if($direction->director)
                                <div class="d-flex align-items-center">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2 small fw-bold" style="width: 25px; height: 25px; font-size: 0.7rem;">
                                        {{ strtoupper(substr($direction->director->name, 0, 1)) }}
                                    </div>
                                    <span class="small">{{ $direction->director->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Non assigné</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{ $direction->id }}" title="Modifier">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('directions.destroy', $direction->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette direction ?');" class="d-inline">
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
                    <div class="modal fade" id="editModal{{ $direction->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('directions.update', $direction->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Modifier la Direction</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Nom de la Direction *</label>
                                            <input type="text" name="name" class="form-control" value="{{ $direction->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Code usuel *</label>
                                            <input type="text" name="code" class="form-control" value="{{ $direction->code }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Département de rattachement *</label>
                                            <select name="department_id" class="form-select" required>
                                                <option value="">Sélectionner...</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept->id }}" {{ $direction->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Directeur</label>
                                            <select name="director_id" class="form-select">
                                                <option value="">Aucun assigné</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $direction->director_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
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
                            <i class="bi bi-folder-fill fs-1 d-block mb-3 text-light"></i>
                            Aucune direction configurée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($directions->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $directions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('directions.store') }}">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouvelle Direction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom de la Direction *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: Direction des Ressources Humaines">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Code usuel *</label>
                        <input type="text" name="code" class="form-control" required placeholder="DRH, DAF...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Département de rattachement *</label>
                        <select name="department_id" class="form-select" required>
                            <option value="">Sélectionner...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Directeur (Chef de Direction)</label>
                        <select name="director_id" class="form-select">
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
