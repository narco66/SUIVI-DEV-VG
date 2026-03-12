<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\StrategicAxis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Action>
 */
class ActionFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-10 months', '+1 month');
        $end = (clone $start)->modify('+' . fake()->numberBetween(45, 270) . ' days');
        $status = fake()->randomElement([
            'planned',
            'in_progress',
            'completed',
            'delayed',
            'blocked',
            'validated',
            'rejected',
            'cloture',
        ]);

        $progress = match ($status) {
            'planned' => fake()->numberBetween(0, 10),
            'in_progress' => fake()->numberBetween(20, 85),
            'completed', 'validated', 'cloture' => fake()->numberBetween(95, 100),
            'delayed', 'blocked', 'rejected' => fake()->numberBetween(10, 60),
            default => fake()->numberBetween(0, 70),
        };

        return [
            'strategic_axis_id' => StrategicAxis::query()->inRandomOrder()->value('id') ?? StrategicAxis::factory(),
            'title' => 'Action prioritaire : ' . fake()->sentence(4),
            'code' => strtoupper(fake()->bothify('ACT-###')),
            'description' => fake()->sentence(16),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'priority' => fake()->randomElement([1, 2, 2, 3, 4]),
            'status' => $status,
            'progress_rate' => $progress,
        ];
    }
}

