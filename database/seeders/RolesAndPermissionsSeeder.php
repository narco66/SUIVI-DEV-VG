<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions (examples, can be expanded later)
        $permissions = [
            'manage users',
            'manage roles',
            'manage institutions',
            'manage decisions',
            'manage action plans',
            'update progress',
            'validate progress',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles
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

        // give all permissions to super_admin
        Role::findByName('super_admin')->givePermissionTo(Permission::all());

        // Create default Super Admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@ceeac.org'],
            [
                'name' => 'Super Administrateur',
                'password' => Hash::make('password123'),
            ]
        );
        
        $user->assignRole('super_admin');
    }
}
