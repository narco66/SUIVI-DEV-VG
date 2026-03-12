@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-auto me-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Organes</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">Organes CEEAC</h2>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg me-2"></i> Nouvel Organe
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
                        <th class="ps-4 py-3">Nom de l'Organe</th>
                        <th class="py-3">Code</th>
                        <th class="py-3">Niveau d'Autorité</th>
                        <th class="py-3">Organe Rattaché</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organs as $organ)
                    <tr>
                        <td class="ps-4 fw-medium text-dark"><i class="bi bi-star-fill text-primary me-2"></i> {{ $organ->name }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $organ->code }}</span></td>
                        <td>
                            @if($organ->level == 1)
                                <span class="badge bg-danger">Suprême (Lvl. 1)</span>
                            @elseif($organ->level == 2)
                                <span class="badge bg-warning text-dark">Lvl. 2</span>
                            @else
                                <span class="badge bg-light text-dark border">Lvl. {{ $organ->level ?: '-' }}</span>
                            @endif
                        </td>
                        <td>{{ $organ->parent ? $organ->parent->name : '-' }}</td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{ $organ->id }}" title="Modifier">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('organs.destroy', $organ->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet organe ?');" class="d-inline">
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
                    <div class="modal fade" id="editModal{{ $organ->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('organs.update', $organ->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Modifier l'Organe</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Nom de l'Organe *</label>
                                            <input type="text" name="name" class="form-control" value="{{ $organ->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Code (ex: CCEG) *</label>
                                            <input type="text" name="code" class="form-control" value="{{ $organ->code }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Niveau d'Autorité (1 = max)</label>
                                            <input type="number" name="level" class="form-control" value="{{ $organ->level }}" min="1">
                                            <div class="form-text">Exemple : Conférence = 1, Conseil = 2...</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Organe Parent (Tutelle)</label>
                                            <select name="parent_id" class="form-select">
                                                <option value="">Aucun ou Indépendant</option>
                                                @foreach($parentOrgans as $parent)
                                                    @if($parent->id !== $organ->id)
                                                        <option value="{{ $parent->id }}" {{ $organ->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                                    @endif
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
                            <i class="bi bi-star-fill fs-1 d-block mb-3 text-light"></i>
                            Aucun organe configuré.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($organs->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $organs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('organs.store') }}">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouvel Organe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom de l'Organe *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: Conférence des Chefs d'État">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Code usuel *</label>
                        <input type="text" name="code" class="form-control" required placeholder="CCEG, CM...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Niveau d'Autorité (1 = max)</label>
                        <input type="number" name="level" class="form-control" value="1" min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Organe Parent (Tutelle)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">Aucun ou Indépendant</option>
                            @foreach($parentOrgans as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
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
