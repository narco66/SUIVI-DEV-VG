<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Direction;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadershipAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $departmentLeaders = [
            'DEP-PAS' => 'h.mampuya@ceeac.int',
            'DEP-ECO' => 'a.ngoma@ceeac.int',
            'DEP-INF' => 'b.nguema@ceeac.int',
            'DEP-ADM' => 'i.moukoko@ceeac.int',
            'DEP-COO' => 'jp.ekani@ceeac.int',
        ];

        foreach ($departmentLeaders as $depCode => $email) {
            $userId = User::where('email', $email)->value('id');
            Department::where('code', $depCode)->update(['commissaire_id' => $userId]);
        }

        $directionLeaders = [
            'DIR-PAS-ALR' => 'joel.bissila@ceeac.int',
            'DIR-PAS-CIV' => 'madeleine.yondo@ceeac.int',
            'DIR-ECO-COM' => 'denis.nze@ceeac.int',
            'DIR-ECO-FIN' => 'caroline.itoua@ceeac.int',
            'DIR-INF-TRA' => 'albert.ngassa@ceeac.int',
            'DIR-INF-ENE' => 'mireille.mbadinga@ceeac.int',
            'DIR-ADM-RH' => 'fidele.mouele@ceeac.int',
            'DIR-ADM-AFI' => 'louise.toko@ceeac.int',
            'DIR-COO-SUI' => 'didier.ekoto@ceeac.int',
            'DIR-COO-PRO' => 'claire.mouloungui@ceeac.int',
        ];

        foreach ($directionLeaders as $dirCode => $email) {
            $userId = User::where('email', $email)->value('id');
            Direction::where('code', $dirCode)->update(['director_id' => $userId]);
        }
    }
}

