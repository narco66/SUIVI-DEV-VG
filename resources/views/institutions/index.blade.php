@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-auto me-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Institutions</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">Institutions</h2>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg me-2"></i> Nouvelle Institution
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
                        <th class="ps-4 py-3">Nom Institution</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Code</th>
                        <th class="py-3">Pays (Si applicable)</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($institutions as $inst)
                    <tr>
                        <td class="ps-4 fw-medium text-dark"><i class="bi bi-building text-primary me-2"></i> {{ $inst->name }}</td>
                        <td>
                            @if($inst->type_id === 'ceeac')
                                <span class="badge bg-primary">CEEAC</span>
                            @elseif($inst->type_id === 'etat_membre')
                                <span class="badge bg-info text-dark">État Membre</span>
                            @else
                                <span class="badge bg-secondary">Partenaire</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $inst->code }}</span></td>
                        <td>{{ $inst->country ? $inst->country->name : '-' }}</td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{ $inst->id }}" title="Modifier">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('institutions.destroy', $inst->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette institution ?');" class="d-inline">
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
                    <div class="modal fade" id="editModal{{ $inst->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('institutions.update', $inst->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Modifier l'Institution</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Nom de l'Institution *</label>
                                            <input type="text" name="name" class="form-control" value="{{ $inst->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Code usuel *</label>
                                            <input type="text" name="code" class="form-control" value="{{ $inst->code }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Type d'Institution *</label>
                                            <select name="type_id" class="form-select" required>
                                                <option value="ceeac" {{ $inst->type_id == 'ceeac' ? 'selected' : '' }}>Organe communautaire (CEEAC)</option>
                                                <option value="etat_membre" {{ $inst->type_id == 'etat_membre' ? 'selected' : '' }}>Ministère ou Institution d'État Membre</option>
                                                <option value="partenaire" {{ $inst->type_id == 'partenaire' ? 'selected' : '' }}>Partenaire Technique/Financier</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Pays d'attachement (Optionnel)</label>
                                            <select name="country_id" class="form-select">
                                                <option value="">Aucun ou Transnational</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ $inst->country_id == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
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
                            <i class="bi bi-bank fs-1 d-block mb-3 text-light"></i>
                            Aucune institution configurée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($institutions->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $institutions->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('institutions.store') }}">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouvelle Institution</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom de l'Institution *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: Commission de la CEEAC">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Code usuel *</label>
                        <input type="text" name="code" class="form-control" required placeholder="COM, MIN...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Type d'Institution *</label>
                        <select name="type_id" class="form-select" required>
                            <option value="ceeac" selected>Organe communautaire (CEEAC)</option>
                            <option value="etat_membre">Ministère ou Institution d'État Membre</option>
                            <option value="partenaire">Partenaire Technique/Financier</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Pays d'attachement (Optionnel)</label>
                        <select name="country_id" class="form-select">
                            <option value="">Aucun ou Transnational</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
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
