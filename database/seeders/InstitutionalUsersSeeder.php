<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InstitutionalUsersSeeder extends Seeder
{
    public function run(): void
    {
        $commission = Institution::where('code', 'CEEAC-COM')->first();
        $departments = Department::pluck('id', 'code');

        $coreUsers = [
            ['name' => 'Nadine Obiang', 'email' => 'n.obiang@ceeac.int', 'role' => 'presidence', 'department' => null],
            ['name' => 'Patrick Mvondo', 'email' => 'p.mvondo@ceeac.int', 'role' => 'vice_presidence', 'department' => null],
            ['name' => 'Rosalie Kintia', 'email' => 'r.kintia@ceeac.int', 'role' => 'secretaire_general', 'department' => 'DEP-ADM'],
            ['name' => 'Frederic Mba', 'email' => 'f.mba@ceeac.int', 'role' => 'admin_technique', 'department' => 'DEP-COO'],
            ['name' => 'Sylvie Etoundi', 'email' => 's.etoundi@ceeac.int', 'role' => 'admin_metier', 'department' => 'DEP-COO'],
            ['name' => 'Herve Mampuya', 'email' => 'h.mampuya@ceeac.int', 'role' => 'commissaire', 'department' => 'DEP-PAS'],
            ['name' => 'Ariane Ngoma', 'email' => 'a.ngoma@ceeac.int', 'role' => 'commissaire', 'department' => 'DEP-ECO'],
            ['name' => 'Blaise Nguema', 'email' => 'b.nguema@ceeac.int', 'role' => 'commissaire', 'department' => 'DEP-INF'],
            ['name' => 'Irene Moukoko', 'email' => 'i.moukoko@ceeac.int', 'role' => 'commissaire', 'department' => 'DEP-ADM'],
            ['name' => 'Jean-Pierre Ekani', 'email' => 'jp.ekani@ceeac.int', 'role' => 'commissaire', 'department' => 'DEP-COO'],
        ];

        foreach ($coreUsers as $profile) {
            $user = User::updateOrCreate(
                ['email' => $profile['email']],
                [
                    'name' => $profile['name'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'institution_id' => $commission?->id,
                    'department_id' => $profile['department'] ? ($departments[$profile['department']] ?? null) : null,
                ]
            );

            $this->assignRoleIfExists($user, $profile['role']);
        }

        $directeurs = [
            'DIR-PAS-ALR' => ['Joel Bissila', 'joel.bissila@ceeac.int'],
            'DIR-PAS-CIV' => ['Madeleine Yondo', 'madeleine.yondo@ceeac.int'],
            'DIR-ECO-COM' => ['Denis Nze', 'denis.nze@ceeac.int'],
            'DIR-ECO-FIN' => ['Caroline Itoua', 'caroline.itoua@ceeac.int'],
            'DIR-INF-TRA' => ['Albert Ngassa', 'albert.ngassa@ceeac.int'],
            'DIR-INF-ENE' => ['Mireille Mbadinga', 'mireille.mbadinga@ceeac.int'],
            'DIR-ADM-RH' => ['Fidele Mouele', 'fidele.mouele@ceeac.int'],
            'DIR-ADM-AFI' => ['Louise Toko', 'louise.toko@ceeac.int'],
            'DIR-COO-SUI' => ['Didier Ekoto', 'didier.ekoto@ceeac.int'],
            'DIR-COO-PRO' => ['Claire Mouloungui', 'claire.mouloungui@ceeac.int'],
        ];

        foreach ($directeurs as $code => $profile) {
            $departmentId = Department::whereHas('directions', fn ($q) => $q->where('code', $code))->value('id');
            $user = User::updateOrCreate(
                ['email' => $profile[1]],
                [
                    'name' => $profile[0],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'institution_id' => $commission?->id,
                    'department_id' => $departmentId,
                ]
            );
            $this->assignRoleIfExists($user, 'directeur_technique');
            $this->assignRoleIfExists($user, 'validateur');
        }

        $coordNames = [
            ['Arnaud Bakala', 'a.bakala@coord-ceeac.org'],
            ['Melissa Ondo', 'm.ondo@coord-ceeac.org'],
            ['Gerard Mpassi', 'g.mpassi@coord-ceeac.org'],
            ['Sonia Meyi', 's.meyi@coord-ceeac.org'],
            ['Cecile Moundounga', 'c.moundounga@coord-ceeac.org'],
            ['Julien Mifoumou', 'j.mifoumou@coord-ceeac.org'],
            ['Helene Ndong', 'h.ndong@coord-ceeac.org'],
            ['Constant Nganongo', 'c.nganongo@coord-ceeac.org'],
            ['Flore Mveng', 'f.mveng@coord-ceeac.org'],
            ['Wilfried Meko', 'w.meko@coord-ceeac.org'],
            ['Sabrina Olinga', 's.olinga@coord-ceeac.org'],
            ['Cyrille Kolla', 'c.kolla@coord-ceeac.org'],
            ['Noemie Engoulou', 'n.engoulou@coord-ceeac.org'],
            ['Brice Penda', 'b.penda@coord-ceeac.org'],
            ['Evelyne Atangana', 'e.atangana@coord-ceeac.org'],
            ['Nora Talla', 'n.talla@coord-ceeac.org'],
            ['Ibrahim Kassongo', 'i.kassongo@coord-ceeac.org'],
            ['Fabrice Banoua', 'f.banoua@coord-ceeac.org'],
            ['Ruth Ndogmo', 'r.ndogmo@coord-ceeac.org'],
            ['Michel Otembe', 'm.otembe@coord-ceeac.org'],
        ];

        foreach ($coordNames as $index => $profile) {
            $departmentId = $departments->values()[$index % max($departments->count(), 1)] ?? null;
            $user = User::updateOrCreate(
                ['email' => $profile[1]],
                [
                    'name' => $profile[0],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'institution_id' => $commission?->id,
                    'department_id' => $departmentId,
                ]
            );
            $this->assignRoleIfExists($user, $index % 3 === 0 ? 'responsable_action' : 'point_focal');
        }

        $stateInstitutions = Institution::where('type_id', 'etat_membre')->get();
        foreach ($stateInstitutions as $institution) {
            $emailPrefix = strtolower(str_replace([' ', '-', "'"], '.', $institution->code));
            $user = User::updateOrCreate(
                ['email' => $emailPrefix . '@etat-ceeac.org'],
                [
                    'name' => 'Point focal national - ' . $institution->name,
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'institution_id' => $institution->id,
                    'department_id' => null,
                ]
            );
            $this->assignRoleIfExists($user, 'point_focal_etat_membre');
        }
    }

    private function assignRoleIfExists(User $user, string $roleName): void
    {
        if (Role::where('name', $roleName)->exists()) {
            $user->syncRoles(array_unique(array_merge($user->roles()->pluck('name')->all(), [$roleName])));
        }
    }
}

