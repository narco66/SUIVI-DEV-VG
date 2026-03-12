@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}" class="text-decoration-none">Rôles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier {{ $role->name }}</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">Modifier le Rôle</h2>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-xl-5">
                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                            <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">{{ $role->name }}</h5>
                                <span class="small text-muted">{{ $role->users()->count() }} utilisateur(s) affecté(s)</span>
                            </div>
                        </div>
                        
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-info-circle me-2"></i>Informations générales</h5>
                        
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium text-dark">Nom du rôle *</label>
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-5 opacity-10">

                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-shield-lock me-2"></i>Habilitations et Permissions</h5>
                        <p class="text-muted small mb-4">Cochez ou décochez les droits liés à ce rôle. Les changements seront instantanément répercutés sur les utilisateurs concernés.</p>

                        <div class="row g-4">
                            @forelse($permissions as $permission)
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-check form-switch custom-switch-lg">
                                        <input class="form-check-input" type="checkbox" role="switch" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light text-center border small text-muted">
                                        <i class="bi bi-info-circle d-block fs-4 mb-2"></i>
                                        Aucune permission système disponible.
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-3">
                            <a href="{{ route('roles.index') }}" class="btn btn-light px-4">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="bi bi-check-circle me-2"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom larger switch for better UX */
.custom-switch-lg .form-check-input {
    width: 2.5em;
    height: 1.25em;
    margin-top: 0.15em;
    cursor: pointer;
}
.custom-switch-lg .form-check-label {
    padding-top: 0.15rem;
    cursor: pointer;
}
</style>
@endsection
