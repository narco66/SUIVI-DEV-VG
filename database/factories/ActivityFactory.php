<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\Activity;
use App\Models\Direction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-10 months', '+1 month');
        $end = (clone $start)->modify('+' . fake()->numberBetween(20, 180) . ' days');
        $status = fake()->randomElement([
            'brouillon',
            'planned',
            'en_cours',
            'in_progress',
            'valide',
            'rejete',
            'en_retard',
            'bloque',
            'cloture',
            'completed',
        ]);

        $progress = match ($status) {
            'brouillon', 'planned' => fake()->numberBetween(0, 15),
            'en_cours', 'in_progress' => fake()->numberBetween(20, 85),
            'valide', 'cloture', 'completed' => fake()->numberBetween(95, 100),
            'rejete', 'en_retard', 'bloque' => fake()->numberBetween(10, 60),
            default => fake()->numberBetween(0, 80),
        };

        return [
            'action_id' => Action::query()->inRandomOrder()->value('id') ?? Action::factory(),
            'title' => 'Activite operationnelle : ' . fake()->sentence(4),
            'code' => 'ACV-' . fake()->unique()->bothify('####-??'),
            'description' => fake()->sentence(18),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'budget' => fake()->randomFloat(2, 5000, 250000),
            'status' => $status,
            'progress_rate' => $progress,
            'direction_id' => Direction::query()->inRandomOrder()->value('id'),
        ];
    }
}

