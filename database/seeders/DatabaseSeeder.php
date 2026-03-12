<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            ReferenceGeoSeeder::class,
            GovernanceReferenceSeeder::class,
            OrganizationStructureSeeder::class,
            InstitutionalUsersSeeder::class,
            LeadershipAssignmentSeeder::class,
            OperationalPlanningSeeder::class,
            MonitoringWorkflowSeeder::class,
        ]);
    }
}
