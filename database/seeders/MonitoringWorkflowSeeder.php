<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\ActionPlan;
use App\Models\Activity;
use App\Models\ActorAssignment;
use App\Models\AuditLog;
use App\Models\Country;
use App\Models\Decision;
use App\Models\Document;
use App\Models\Institution;
use App\Models\ProgressUpdate;
use App\Models\User;
use App\Models\Validation;
use App\Support\InstitutionalText;
use Illuminate\Database\Seeder;

class MonitoringWorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $institutions = Institution::all();
        $countries = Country::all();

        if ($users->isEmpty()) {
            return;
        }

        $assignablePool = collect()
            ->merge(Decision::inRandomOrder()->limit(35)->get())
            ->merge(ActionPlan::inRandomOrder()->limit(60)->get())
            ->merge(Action::inRandomOrder()->limit(180)->get())
            ->merge(Activity::inRandomOrder()->limit(260)->get());

        foreach ($assignablePool as $item) {
            $types = ['responsable', 'point_focal', 'validateur'];
            $count = rand(1, 2);
            for ($i = 0; $i < $count; $i++) {
                ActorAssignment::create([
                    'assignable_type' => get_class($item),
                    'assignable_id' => $item->id,
                    'user_id' => $users->random()->id,
                    'institution_id' => $institutions->isNotEmpty() ? $institutions->random()->id : null,
                    'country_id' => $countries->isNotEmpty() ? $countries->random()->id : null,
                    'type' => $types[array_rand($types)],
                    'start_date' => now()->subDays(rand(20, 320))->toDateString(),
                    'end_date' => rand(1, 10) <= 2 ? now()->addDays(rand(30, 300))->toDateString() : null,
                ]);
            }
        }

        $updatablePool = collect()
            ->merge(Decision::inRandomOrder()->limit(30)->get())
            ->merge(ActionPlan::inRandomOrder()->limit(55)->get())
            ->merge(Action::inRandomOrder()->limit(160)->get())
            ->merge(Activity::inRandomOrder()->limit(220)->get());

        $updates = collect();
        foreach ($updatablePool as $item) {
            $updatesCount = rand(1, 3);
            for ($u = 0; $u < $updatesCount; $u++) {
                $status = collect(['pending', 'validated', 'rejected'])->random();
                $update = ProgressUpdate::create([
                    'updatable_type' => get_class($item),
                    'updatable_id' => $item->id,
                    'progress_rate' => rand(5, 100),
                    'achievements' => InstitutionalText::achievement(),
                    'difficulties' => InstitutionalText::difficulty(),
                    'next_steps' => InstitutionalText::nextStep(),
                    'support_needs' => InstitutionalText::supportNeed(),
                    'status' => $status,
                    'author_id' => $users->random()->id,
                    'submitted_at' => now()->subDays(rand(0, 280)),
                ]);

                $updates->push($update);
            }
        }

        foreach ($updates as $update) {
            if ($update->status === 'pending') {
                continue;
            }

            $validationStatus = $update->status === 'validated' ? 'approved' : 'rejected';
            Validation::create([
                'update_id' => $update->id,
                'validator_id' => $users->random()->id,
                'status' => $validationStatus,
                'level' => rand(1, 3),
                'comment' => InstitutionalText::validationComment($validationStatus),
                'validated_at' => now()->subDays(rand(0, 180)),
            ]);
        }

        $documentablePool = collect()
            ->merge(Decision::inRandomOrder()->limit(20)->get())
            ->merge(ActionPlan::inRandomOrder()->limit(40)->get())
            ->merge(Action::inRandomOrder()->limit(120)->get())
            ->merge(Activity::inRandomOrder()->limit(180)->get())
            ->merge(ProgressUpdate::inRandomOrder()->limit(120)->get());

        foreach ($documentablePool as $item) {
            $docsCount = rand(1, 2);
            for ($d = 0; $d < $docsCount; $d++) {
                $type = collect(['pdf', 'docx', 'xlsx', 'pptx', 'png'])->random();
                Document::create([
                    'documentable_type' => get_class($item),
                    'documentable_id' => $item->id,
                    'title' => collect([
                        'Rapport periodique de mise en oeuvre',
                        'Note de suivi des recommandations',
                        'Matrice des risques actualisee',
                        'Annexe des indicateurs de performance',
                        'Piece justificative de terrain',
                    ])->random(),
                    'type' => $type,
                    'category' => collect(['justificatif', 'rapport', 'acte', 'compte_rendu', 'notification'])->random(),
                    'path' => 'documents/demo/' . strtolower(class_basename($item)) . '/' . uniqid('doc_') . '.' . $type,
                    'size' => rand(180, 9200),
                    'uploader_id' => $users->random()->id,
                    'uploaded_at' => now()->subDays(rand(0, 220)),
                ]);
            }
        }

        $auditablePool = collect()
            ->merge(Decision::inRandomOrder()->limit(25)->get())
            ->merge(ActionPlan::inRandomOrder()->limit(40)->get())
            ->merge(Action::inRandomOrder()->limit(120)->get())
            ->merge(Activity::inRandomOrder()->limit(180)->get())
            ->merge(ProgressUpdate::inRandomOrder()->limit(140)->get());

        foreach ($auditablePool as $item) {
            $eventsCount = rand(1, 3);
            for ($e = 0; $e < $eventsCount; $e++) {
                $oldProgress = rand(5, 75);
                $newProgress = min(100, $oldProgress + rand(5, 20));

                AuditLog::create([
                    'user_id' => $users->random()->id,
                    'action' => collect(['created', 'updated', 'validated', 'rejected', 'closed'])->random(),
                    'auditable_type' => get_class($item),
                    'auditable_id' => $item->id,
                    'old_values' => ['progress_rate' => $oldProgress, 'status' => 'in_progress'],
                    'new_values' => ['progress_rate' => $newProgress, 'status' => collect(['in_progress', 'delayed', 'completed', 'blocked'])->random()],
                    'ip_address' => fake()->ipv4(),
                    'created_at' => now()->subDays(rand(0, 280)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

