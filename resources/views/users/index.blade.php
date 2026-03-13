@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row align-items-stretch g-3 mb-4">
        <div class="col-12 col-md me-auto">
            <div class="h-100 rounded-4 border bg-primary bg-opacity-10 p-4 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-shape bg-white text-primary rounded-circle icon-lg shadow-sm">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <p class="text-uppercase fw-semibold text-primary mb-1" style="font-size: 0.72rem; letter-spacing: 0.08em;">Administration</p>
                        <h2 class="h3 fw-bold text-dark mb-1">Gestion des Utilisateurs</h2>
                        <p class="text-muted mb-2">Administrez les comptes, rôles d'accès et rattachements institutionnels de la plateforme.</p>
                        <span class="badge bg-white text-primary border fw-semibold px-3 py-2">
                            {{ $users->total() }} utilisateur(s) actif(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-auto d-flex align-items-stretch">
            <div class="h-100 w-100 d-flex flex-column justify-content-between rounded-4 border bg-white p-4 shadow-sm gap-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none">Administration</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
                    </ol>
                </nav>
                <a href="{{ route('users.create') }}" class="btn btn-primary px-4 w-100">
                    <i class="bi bi-person-plus me-2"></i> Nouvel Utilisateur
                </a>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4 w-100">
                    <i class="bi bi-shield-lock me-2"></i> Gérer les Rôles
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning border-0 shadow-sm alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Utilisateur</th>
                        <th class="py-3">Institution / Département</th>
                        <th class="py-3">Rôles d'accès</th>
                        <th class="py-3 text-center">Statut</th>
                        <th class="pe-4 py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 45px; height: 45px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <div class="small text-muted">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small">
                                @if($user->institution)
                                    <div class="fw-medium text-dark"><i class="bi bi-bank me-1 text-muted"></i> {{ $user->institution->name }}</div>
                                @else
                                    <div class="text-muted"><i class="bi bi-bank me-1"></i> Transversal</div>
                                @endif

                                @if($user->department)
                                    <div class="text-muted mt-1"><i class="bi bi-diagram-2 me-1"></i> {{ $user->department->name }}</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                    @if($role->name === 'Super Admin')
                                        <span class="badge bg-danger bg-gradient">Super Admin</span>
                                    @else
                                        <span class="badge bg-warning text-dark border border-warning">{{ $role->name }}</span>
                                    @endif
                                @empty
                                    <span class="badge bg-light text-muted border">Aucun rôle</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="text-center">
                            @if(Cache::has('user-is-online-' . $user->id))
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-circle-fill small me-1"></i> En ligne</span>
                            @else
                                <span class="badge bg-light text-secondary border px-2 py-1"><i class="bi bi-circle small me-1"></i> Hors ligne</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light border text-primary" title="Modifier l'utilisateur">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="Supprimer l'utilisateur" {{ $user->hasRole('Super Admin') ? 'disabled' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-light border text-muted" title="Vous ne pouvez pas vous supprimer vous-même" disabled>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people d-block mb-3 fs-1 text-light"></i>
                            Aucun compte utilisateur trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white border-top-0 pt-0 pb-3">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection