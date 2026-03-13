<?php

namespace App\Policies;

use App\Models\Decision;
use App\Models\User;

class DecisionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('decisions.view') || $user->hasAnyRole(['super_admin', 'admin_technique', 'admin_metier']);
    }

    public function view(User $user, Decision $decision): bool
    {
        return $user->can('decisions.view') || $user->id === $decision->created_by || $user->hasAnyRole(['super_admin', 'admin_technique', 'admin_metier']);
    }

    public function create(User $user): bool
    {
        return $user->can('decisions.create') || $user->hasAnyRole(['super_admin', 'admin_technique', 'admin_metier', 'point_focal']);
    }

    public function update(User $user, Decision $decision): bool
    {
        return $user->can('decisions.update') || $user->id === $decision->created_by || $user->hasAnyRole(['super_admin', 'admin_technique', 'admin_metier']);
    }

    public function delete(User $user, Decision $decision): bool
    {
        return $user->can('decisions.delete') || $user->hasAnyRole(['super_admin', 'admin_technique']);
    }
}
