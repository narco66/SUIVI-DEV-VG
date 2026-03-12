@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-auto me-auto">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rôles et Habilitations</li>
                </ol>
            </nav>
            <h2 class="h3 fw-bold text-dark mb-0">Gestion des Rôles</h2>
        </div>
        <div class="col-12 col-md-auto mt-3 mt-md-0 d-flex gap-2">
            <a href="{{ route('roles.create') }}" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-shield-plus me-2"></i> Créer un Rôle
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                <i class="bi bi-people me-2"></i> Gérer les Utilisateurs
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Nom du Rôle</th>
                        <th class="py-3">Permissions Assignées</th>
                        <th class="py-3 text-center">Utilisateurs</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 text-warning rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-shield-check fs-5"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $role->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($role->name === 'Super Admin')
                                <span class="badge bg-danger">Accès Total (Super Admin)</span>
                            @else
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($role->permissions->take(5) as $permission)
                                        <span class="badge bg-light text-dark border">{{ $permission->name }}</span>
                                    @endforeach
                                    @if($role->permissions->count() > 5)
                                        <span class="badge bg-secondary">+ {{ $role->permissions->count() - 5 }} autres</span>
                                    @endif
                                    @if($role->permissions->count() === 0)
                                        <span class="text-muted small fst-italic">Aucune permission spécifique</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-primary px-3">{{ $role->users_count }}</span>
                        </td>
                        <td class="pe-4 text-end">
                            @if($role->name !== 'Super Admin')
                                <div class="btn-group">
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-light border text-primary" title="Modifier le rôle">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Supprimer le rôle">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="badge bg-light text-muted border" title="Le Super Admin ne peut pas être modifié d'ici"><i class="bi bi-lock-fill"></i> Sécurisé</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-lock d-block mb-3 fs-1 text-light"></i>
                            Aucun rôle configuré pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($roles->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $roles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
