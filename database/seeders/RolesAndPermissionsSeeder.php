<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Admin
            'users.manage',
            'roles.manage',
            'institutions.manage',

            // Decisions
            'decisions.view',
            'decisions.create',
            'decisions.update',
            'decisions.delete',
            'decisions.export',
            'decisions.autosave',

            // Progress/workflow
            'progress.update',
            'progress.validate',
            'reports.view',

            // GED
            'ged.view',
            'ged.search',
            'documents.view',
            'documents.upload',
            'documents.update',
            'documents.delete',
            'documents.download',
            'documents.validate',
            'documents.workflow.manage',
            'documents.confidentiality.manage',
            'documents.audit.view',
            'documents.ocr.run',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'super_admin',
            'admin_technique',
            'admin_metier',
            'presidence',
            'vice_presidence',
            'commissaire',
            'secretaire_general',
            'directeur_technique',
            'responsable_action',
            'validateur',
            'point_focal',
            'point_focal_etat_membre',
            'utilisateur_consultation',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        Role::findByName('super_admin')->syncPermissions(Permission::all());

        Role::findByName('admin_technique')->syncPermissions([
            'users.manage', 'roles.manage', 'institutions.manage',
            'decisions.view', 'decisions.create', 'decisions.update', 'decisions.export', 'decisions.autosave',
            'ged.view', 'ged.search', 'documents.view', 'documents.upload', 'documents.update', 'documents.delete',
            'documents.download', 'documents.workflow.manage', 'documents.confidentiality.manage',
            'documents.audit.view', 'documents.ocr.run',
        ]);

        Role::findByName('admin_metier')->syncPermissions([
            'decisions.view', 'decisions.create', 'decisions.update', 'decisions.export', 'decisions.autosave',
            'progress.update', 'progress.validate', 'reports.view',
            'ged.view', 'ged.search', 'documents.view', 'documents.upload', 'documents.download', 'documents.validate',
        ]);

        Role::findByName('validateur')->syncPermissions([
            'decisions.view', 'ged.view', 'ged.search', 'documents.view', 'documents.download', 'documents.validate',
            'progress.validate',
        ]);

        Role::findByName('point_focal')->syncPermissions([
            'decisions.view', 'decisions.create', 'decisions.update', 'decisions.autosave',
            'progress.update',
            'ged.view', 'ged.search', 'documents.view', 'documents.upload', 'documents.download',
        ]);

        Role::findByName('point_focal_etat_membre')->syncPermissions([
            'decisions.view', 'progress.update', 'ged.view', 'ged.search', 'documents.view', 'documents.upload', 'documents.download',
        ]);

        Role::findByName('utilisateur_consultation')->syncPermissions([
            'decisions.view', 'reports.view', 'ged.view', 'ged.search', 'documents.view', 'documents.download',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'admin@ceeac.org'],
            [
                'name' => 'Super Administrateur',
                'password' => Hash::make('password123'),
            ]
        );

        if (!$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }
    }
}