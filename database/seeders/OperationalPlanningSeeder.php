<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\ActionPlan;
use App\Models\Activity;
use App\Models\Decision;
use App\Models\DecisionCategory;
use App\Models\DecisionType;
use App\Models\Deliverable;
use App\Models\Direction;
use App\Models\Domain;
use App\Models\Institution;
use App\Models\Milestone;
use App\Models\Organ;
use App\Models\Session;
use App\Models\StrategicAxis;
use App\Models\User;
use App\Support\InstitutionalText;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OperationalPlanningSeeder extends Seeder
{
    public function run(): void
    {
        $organs = Organ::all();
        $types = DecisionType::all();
        $categories = DecisionCategory::all();
        $domains = Domain::all();
        $institutions = Institution::all();
        $users = User::all();
        $directions = Direction::pluck('id')->all();

        if ($organs->isEmpty() || $types->isEmpty() || $users->isEmpty()) {
            return;
        }

        $sessions = collect();
        for ($i = 1; $i <= 14; $i++) {
            $start = Carbon::now()->subMonths(rand(2, 24))->subDays(rand(0, 25));
            $end = (clone $start)->addDays(rand(2, 4));
            $status = $end->lt(now()) ? 'closed' : ($start->lte(now()) ? 'ongoing' : 'planned');

            $sessions->push(
                Session::updateOrCreate(
                    ['code' => 'SES-CEEAC-' . $start->format('Y') . '-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT)],
                    [
                        'organ_id' => $organs->random()->id,
                        'type' => rand(1, 4) === 1 ? 'Extraordinaire' : 'Ordinaire',
                        'date_start' => $start->toDateString(),
                        'date_end' => $end->toDateString(),
                        'location' => collect(['Libreville', 'Brazzaville', 'Malabo', 'Yaounde', 'N Djamena', 'Kigali'])->random(),
                        'status' => $status,
                    ]
                )
            );
        }

        $decisionStatuses = ['draft', 'pending_validation', 'active', 'in_progress', 'delayed', 'blocked', 'completed', 'closed', 'cancelled'];
        $activityStatuses = ['brouillon', 'en_cours', 'valide', 'rejete', 'en_retard', 'cloture', 'bloque', 'planned', 'in_progress', 'completed'];

        $globalAxisOrder = ((int) StrategicAxis::max('order')) + 1;
        $actionCodeCounter = ((int) Action::max('id')) + 1;
        $activityCodeCounter = ((int) Activity::max('id')) + 1;

        for ($i = 1; $i <= 68; $i++) {
            $adoption = Carbon::now()->subDays(rand(60, 700));
            $deadline = (clone $adoption)->addDays(rand(120, 600));
            $status = $decisionStatuses[array_rand($decisionStatuses)];
            $progress = $this->progressFromDecisionStatus($status);

            $domain = $domains->random();
            $decision = Decision::updateOrCreate(
                ['code' => 'DEC-CEEAC-' . $adoption->format('Y') . '-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
                [
                    'session_id' => $sessions->random()->id,
                    'title' => InstitutionalText::decisionTitle(strtolower($domain->name)),
                    'summary' => InstitutionalText::shortSummary(),
                    'description' => InstitutionalText::longDescription(),
                    'legal_basis' => InstitutionalText::legalBasis(),
                    'type_id' => $types->random()->id,
                    'category_id' => $categories->isNotEmpty() ? $categories->random()->id : null,
                    'domain_id' => $domain->id,
                    'priority' => collect([1, 2, 2, 3, 3, 4])->random(),
                    'status' => $status,
                    'date_adoption' => $adoption->toDateString(),
                    'date_echeance' => $deadline->toDateString(),
                    'institution_id' => $institutions->random()->id,
                    'progress_rate' => $progress,
                    'created_by' => $users->random()->id,
                ]
            );

            $plansCount = rand(1, 2);
            for ($p = 1; $p <= $plansCount; $p++) {
                $planStart = (clone $adoption)->addDays(rand(5, 45));
                $planEnd = (clone $deadline)->subDays(rand(0, 20));

                $plan = ActionPlan::create([
                    'decision_id' => $decision->id,
                    'title' => "Plan d action {$p} - {$decision->code}",
                    'description' => "Plan operationnel defini pour l'execution graduelle de {$decision->code}.",
                    'start_date' => $planStart->toDateString(),
                    'end_date' => $planEnd->toDateString(),
                    'status' => $this->actionPlanStatusFromDecision($decision->status),
                ]);

                $axesCount = rand(2, 4);
                for ($a = 1; $a <= $axesCount; $a++) {
                    $axis = StrategicAxis::create([
                        'action_plan_id' => $plan->id,
                        'title' => collect([
                            "Renforcement des capacites institutionnelles",
                            "Mecanisme de coordination inter-etats",
                            "Suivi des indicateurs de performance",
                            "Pilotage des risques et conformite",
                            "Capitalisation et redevabilite",
                        ])->random(),
                        'code' => 'AXE-' . $plan->id . '-' . $a,
                        'order' => $globalAxisOrder++,
                        'description' => "Axe prioritaire du {$plan->title} pour structurer l'execution.",
                    ]);

                    $actionsCount = rand(2, 4);
                    for ($ac = 1; $ac <= $actionsCount; $ac++) {
                        $actionStatus = $activityStatuses[array_rand($activityStatuses)];
                        $actionStart = (clone $planStart)->addDays(rand(0, 45));
                        $actionEnd = (clone $planEnd)->subDays(rand(0, 30));
                        if ($actionEnd->lt($actionStart)) {
                            $actionEnd = (clone $actionStart)->addDays(rand(30, 120));
                        }

                        $action = Action::create([
                            'strategic_axis_id' => $axis->id,
                            'title' => collect([
                                "Mettre en place le dispositif de coordination transfrontaliere",
                                "Consolider les rapports nationaux de mise en oeuvre",
                                "Executer les mesures de rattrapage des activites critiques",
                                "Renforcer le cadre de suivi-evaluation communautaire",
                                "Operationaliser le plan de communication institutionnelle",
                            ])->random(),
                            'code' => 'ACT-' . str_pad((string) $actionCodeCounter++, 5, '0', STR_PAD_LEFT),
                            'description' => InstitutionalText::longDescription(),
                            'start_date' => $actionStart->toDateString(),
                            'end_date' => $actionEnd->toDateString(),
                            'priority' => collect([1, 2, 2, 3, 4])->random(),
                            'status' => $actionStatus,
                            'progress_rate' => $this->progressFromGenericStatus($actionStatus),
                        ]);

                        $activitiesCount = rand(2, 5);
                        for ($at = 1; $at <= $activitiesCount; $at++) {
                            $activityStatus = $activityStatuses[array_rand($activityStatuses)];
                            $activityStart = (clone $actionStart)->addDays(rand(0, 20));
                            $activityEnd = (clone $actionEnd)->subDays(rand(0, 15));
                            if ($activityEnd->lt($activityStart)) {
                                $activityEnd = (clone $activityStart)->addDays(rand(15, 90));
                            }

                            $activity = Activity::create([
                                'action_id' => $action->id,
                                'title' => collect([
                                    "Atelier regional de cadrage",
                                    "Mission de suivi terrain",
                                    "Consolidation des donnees nationales",
                                    "Session de validation technique",
                                    "Production du rapport periodique",
                                    "Collecte des pieces justificatives",
                                ])->random(),
                                'code' => 'ACV-' . str_pad((string) $activityCodeCounter++, 6, '0', STR_PAD_LEFT),
                                'description' => InstitutionalText::shortSummary(),
                                'start_date' => $activityStart->toDateString(),
                                'end_date' => $activityEnd->toDateString(),
                                'budget' => rand(5000, 150000),
                                'status' => $activityStatus,
                                'progress_rate' => $this->progressFromGenericStatus($activityStatus),
                                'direction_id' => !empty($directions) ? $directions[array_rand($directions)] : null,
                            ]);

                            $milestonesCount = rand(1, 3);
                            for ($m = 1; $m <= $milestonesCount; $m++) {
                                $expected = (clone $activityStart)->addDays(rand(10, 90));
                                $milestoneStatus = collect(['pending', 'in_progress', 'completed', 'delayed', 'blocked', 'validated', 'rejected'])->random();
                                $achieved = in_array($milestoneStatus, ['completed', 'validated'], true)
                                    ? (clone $expected)->addDays(rand(-8, 20))
                                    : null;

                                Milestone::create([
                                    'activity_id' => $activity->id,
                                    'title' => "Jalon {$m} - {$activity->title}",
                                    'expected_date' => $expected->toDateString(),
                                    'achieved_date' => $achieved?->toDateString(),
                                    'status' => $milestoneStatus,
                                ]);
                            }

                            $deliverablesCount = rand(1, 2);
                            for ($d = 1; $d <= $deliverablesCount; $d++) {
                                Deliverable::create([
                                    'activity_id' => $activity->id,
                                    'title' => collect([
                                        "Rapport d execution mensuel",
                                        "Matrice de suivi consolidee",
                                        "Compte rendu de concertation",
                                        "Note de recommandations techniques",
                                    ])->random(),
                                    'description' => InstitutionalText::shortSummary(),
                                    'expected_date' => (clone $activityEnd)->subDays(rand(0, 20))->toDateString(),
                                    'status' => collect(['pending', 'in_progress', 'completed', 'delayed', 'validated', 'rejected'])->random(),
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    private function progressFromDecisionStatus(string $status): int
    {
        return match ($status) {
            'draft' => rand(0, 12),
            'pending_validation' => rand(25, 65),
            'active', 'in_progress' => rand(30, 85),
            'delayed', 'blocked' => rand(10, 55),
            'completed', 'closed' => rand(95, 100),
            'cancelled' => rand(0, 40),
            default => rand(0, 70),
        };
    }

    private function actionPlanStatusFromDecision(string $decisionStatus): string
    {
        return match ($decisionStatus) {
            'draft' => 'draft',
            'completed', 'closed' => 'completed',
            'blocked', 'cancelled' => 'suspended',
            default => 'active',
        };
    }

    private function progressFromGenericStatus(string $status): int
    {
        return match ($status) {
            'brouillon', 'planned' => rand(0, 15),
            'en_cours', 'in_progress' => rand(20, 85),
            'valide', 'cloture', 'completed' => rand(95, 100),
            'rejete', 'en_retard', 'bloque', 'delayed', 'blocked' => rand(10, 60),
            default => rand(0, 80),
        };
    }
}
