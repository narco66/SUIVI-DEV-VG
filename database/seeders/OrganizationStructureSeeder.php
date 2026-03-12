<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Direction;
use App\Models\Institution;
use Illuminate\Database\Seeder;

class OrganizationStructureSeeder extends Seeder
{
    public function run(): void
    {
        $commission = Institution::where('code', 'CEEAC-COM')->first();
        if (!$commission) {
            return;
        }

        $departments = [
            'DEP-PAS' => 'Departement Paix, Securite et Stabilite',
            'DEP-ECO' => 'Departement Integration Economique',
            'DEP-INF' => 'Departement Infrastructures et Energie',
            'DEP-ADM' => 'Departement Administration et Ressources',
            'DEP-COO' => 'Departement Coordination des Programmes',
        ];

        $departmentMap = [];
        foreach ($departments as $code => $name) {
            $department = Department::updateOrCreate(
                ['code' => $code],
                [
                    'institution_id' => $commission->id,
                    'name' => $name,
                    'commissaire_id' => null,
                ]
            );
            $departmentMap[$code] = $department->id;
        }

        $directions = [
            ['code' => 'DIR-PAS-ALR', 'department' => 'DEP-PAS', 'name' => 'Direction Alerte Precoce'],
            ['code' => 'DIR-PAS-CIV', 'department' => 'DEP-PAS', 'name' => 'Direction Protection Civile et Securite Humaine'],
            ['code' => 'DIR-ECO-COM', 'department' => 'DEP-ECO', 'name' => 'Direction Commerce et Integration des Marches'],
            ['code' => 'DIR-ECO-FIN', 'department' => 'DEP-ECO', 'name' => 'Direction Financement du Developpement'],
            ['code' => 'DIR-INF-TRA', 'department' => 'DEP-INF', 'name' => 'Direction Transports et Corridors'],
            ['code' => 'DIR-INF-ENE', 'department' => 'DEP-INF', 'name' => 'Direction Energie et Transition Verte'],
            ['code' => 'DIR-ADM-RH', 'department' => 'DEP-ADM', 'name' => 'Direction Ressources Humaines'],
            ['code' => 'DIR-ADM-AFI', 'department' => 'DEP-ADM', 'name' => 'Direction Affaires Financieres'],
            ['code' => 'DIR-COO-SUI', 'department' => 'DEP-COO', 'name' => 'Direction Suivi et Evaluation'],
            ['code' => 'DIR-COO-PRO', 'department' => 'DEP-COO', 'name' => 'Direction Programmes Regionaux'],
        ];

        foreach ($directions as $direction) {
            Direction::updateOrCreate(
                ['code' => $direction['code']],
                [
                    'department_id' => $departmentMap[$direction['department']] ?? array_values($departmentMap)[0],
                    'name' => $direction['name'],
                    'director_id' => null,
                ]
            );
        }
    }
}

