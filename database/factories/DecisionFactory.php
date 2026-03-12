<?php

namespace Database\Factories;

use App\Models\Decision;
use App\Models\DecisionCategory;
use App\Models\DecisionType;
use App\Models\Domain;
use App\Models\Institution;
use App\Models\Session;
use App\Models\User;
use App\Support\InstitutionalText;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Decision>
 */
class DecisionFactory extends Factory
{
    public function definition(): array
    {
        $adoption = fake()->dateTimeBetween('-15 months', '-1 month');
        $deadline = (clone $adoption)->modify('+' . fake()->numberBetween(60, 420) . ' days');
        $status = fake()->randomElement([
            'draft', 'pending_validation', 'active', 'in_progress', 'delayed', 'blocked', 'completed', 'closed', 'cancelled',
        ]);

        $progress = match ($status) {
            'draft' => fake()->numberBetween(0, 15),
            'pending_validation' => fake()->numberBetween(35, 70),
            'active', 'in_progress' => fake()->numberBetween(20, 85),
            'delayed', 'blocked' => fake()->numberBetween(10, 60),
            'completed', 'closed' => fake()->numberBetween(95, 100),
            default => fake()->numberBetween(0, 40),
        };

        return [
            'session_id' => Session::query()->inRandomOrder()->value('id') ?? Session::factory(),
            'code' => 'DEC-' . fake()->unique()->numerify('####') . '-' . strtoupper(fake()->lexify('??')),
            'title' => InstitutionalText::decisionTitle(fake()->randomElement(['securite', 'integration economique', 'sante', 'infrastructures'])),
            'summary' => InstitutionalText::shortSummary(),
            'description' => InstitutionalText::longDescription(),
            'legal_basis' => InstitutionalText::legalBasis(),
            'type_id' => DecisionType::query()->inRandomOrder()->value('id') ?? DecisionType::factory(),
            'category_id' => DecisionCategory::query()->inRandomOrder()->value('id') ?? DecisionCategory::factory(),
            'domain_id' => Domain::query()->inRandomOrder()->value('id') ?? Domain::factory(),
            'priority' => fake()->randomElement([1, 2, 2, 3, 3, 4]),
            'status' => $status,
            'date_adoption' => $adoption->format('Y-m-d'),
            'date_echeance' => $deadline->format('Y-m-d'),
            'institution_id' => Institution::query()->inRandomOrder()->value('id') ?? Institution::factory(),
            'progress_rate' => $progress,
            'created_by' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
        ];
    }
}

