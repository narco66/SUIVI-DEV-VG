@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-decoration-none">Utilisateurs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier {{ $user->name }}</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">Modifier l'Utilisateur</h2>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">
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

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-xl-5">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="d-flex align-items-center mb-5 pb-3 border-bottom">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $user->name }}</h4>
                        <span class="text-muted">Membre depuis le {{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="row g-5">
                    <!-- Colonne de gauche: Infos de base -->
                    <div class="col-xl-6">
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-person-vcard me-2"></i>Profil et Identifiants</h5>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium text-dark">Nom complet *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 pb-3 border-bottom">
                            <label for="email" class="form-label fw-medium text-dark">Adresse E-mail *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-light border shadow-sm">
                            <div class="d-flex mb-3 align-items-center">
                                <i class="bi bi-shield-lock-fill text-warning fs-4 me-2"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Renouvellement du mot de passe</h6>
                                    <span class="small text-muted">Laissez vide pour conserver l'actuel.</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="password" class="form-label fw-medium text-dark small">Nouveau mot de passe</label>
                                    <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" id="password" name="password">
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-medium text-dark small">Confirmer</label>
                                    <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne de droite: Rattachement institutionnel & Rôles -->
                    <div class="col-xl-6">
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-building me-2"></i>Affiliation & Droits d'accès</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="institution_id" class="form-label fw-medium text-dark">Institution</label>
                                <select class="form-select @error('institution_id') is-invalid @enderror" id="institution_id" name="institution_id">
                                    <option value="">-- Non rattaché / Transversal --</option>
                                    @foreach($institutions as $inst)
                                        <option value="{{ $inst->id }}" {{ old('institution_id', $user->institution_id) == $inst->id ? 'selected' : '' }}>
                                            {{ $inst->name }} ({{ $inst->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label fw-medium text-dark">Département interne</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" id="department_id" name="department_id">
                                    <option value="">-- Aucun département --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-medium text-dark d-block">Rôles assignés</label>
                            <div class="card bg-light border-0">
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        @forelse($roles as $role)
                                        <div class="col-sm-6">
                                            <div class="form-check custom-checkbox">
                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" {{ collect(old('roles', $userRoles))->contains($role->name) ? 'checked' : '' }}>
                                                <label class="form-check-label ps-1" for="role_{{ $role->id }}">
                                                    {{ $role->name }}
                                                    @if($role->name === 'Super Admin')
                                                        <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Attention: Accès total au système"></i>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="col-12 text-muted small fst-italic">
                                            Aucun rôle défini dans le système.
                                        </div>
                                        @endforelse
                                    </div>
                                    @if(auth()->id() === $user->id && $user->hasRole('Super Admin'))
                                        <div class="alert alert-warning py-2 mb-0 mt-3 small">
                                            <i class="bi bi-lock-fill me-1"></i> Votre rôle <strong>Super Admin</strong> est protégé. Vous ne pouvez pas vous l'enlever tout seul.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn btn-light px-4">Annuler</a>
                    <button type="submit" class="btn btn-primary px-5">
                        <i class="bi bi-check-circle me-2"></i> Mettre à jour l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.custom-checkbox .form-check-input {
    width: 1.25em;
    height: 1.25em;
    cursor: pointer;
}
.custom-checkbox .form-check-label {
    padding-top: 0.1rem;
    cursor: pointer;
}
</style>
@endsection
