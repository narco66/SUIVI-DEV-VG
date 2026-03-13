@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-globe-americas fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Administration</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Gestion des Pays</h2>
                        <p class="text-muted mb-2">Administrez les États membres avec leur code ISO et leur zone géographique de référence.</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">
                            {{ $countries->total() }} pays configuré(s)
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
                        <li class="breadcrumb-item active" aria-current="page">Pays (États Membres)</li>
                    </ol>
                </nav>
                <button type="button" class="btn btn-primary px-4 w-100" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg me-2"></i> Ajouter un Pays
                </button>
            </div>
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
                        <th class="ps-4 py-3">Nom du Pays</th>
                        <th class="py-3">Code ISO</th>
                        <th class="py-3">Région/Zone</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($countries as $country)
                    <tr>
                        <td class="ps-4 fw-medium text-dark"><i class="bi bi-geo-alt text-primary me-2"></i> {{ $country->name }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $country->code_iso ?: 'N/A' }}</span></td>
                        <td class="text-muted small">{{ $country->region ?: '-' }}</td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editModal{{ $country->id }}" title="Modifier">
                                    <i class="bi bi-pencil text-primary"></i>
                                </button>
                                <form action="{{ route('countries.destroy', $country->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pays ?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $country->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('countries.update', $country->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h5 class="modal-title fw-bold">Modifier le Pays</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Nom du Pays *</label>
                                            <input type="text" name="name" class="form-control" value="{{ $country->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Code ISO (ex: GAB, CMR...) *</label>
                                            <input type="text" name="code_iso" class="form-control" value="{{ $country->code_iso }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Région</label>
                                            <input type="text" name="region" class="form-control" value="{{ $country->region }}" placeholder="CEMAC, Afrique Centrale...">
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
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-globe-americas fs-1 d-block mb-3 text-light"></i>
                            Aucun pays configuré.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($countries->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $countries->links() }}
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('countries.store') }}">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Nouveau Pays</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom du Pays *</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: République Gabonaise">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Code ISO (ex: GAB) *</label>
                        <input type="text" name="code_iso" class="form-control" required placeholder="GAB">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Région</label>
                        <input type="text" name="region" class="form-control" placeholder="Afrique Centrale, CEMAC...">
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