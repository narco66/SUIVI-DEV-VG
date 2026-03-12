<?php

namespace Database\Factories;

use App\Models\ActionPlan;
use App\Models\Decision;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActionPlan>
 */
class ActionPlanFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-12 months', '+2 months');
        $end = (clone $start)->modify('+' . fake()->numberBetween(90, 540) . ' days');
        $status = fake()->randomElement(['draft', 'active', 'completed', 'suspended']);

        return [
            'decision_id' => Decision::query()->inRandomOrder()->value('id') ?? Decision::factory(),
            'title' => 'Plan d action ' . fake()->sentence(4),
            'description' => fake()->sentence(18),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'status' => $status,
        ];
    }
}

