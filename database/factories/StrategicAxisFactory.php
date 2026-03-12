<?php

namespace Database\Factories;

use App\Models\ActionPlan;
use App\Models\StrategicAxis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StrategicAxis>
 */
class StrategicAxisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'action_plan_id' => ActionPlan::query()->inRandomOrder()->value('id') ?? ActionPlan::factory(),
            'title' => 'Axe strategique ' . fake()->sentence(3),
            'code' => strtoupper(fake()->bothify('AXE-##')),
            'order' => fake()->numberBetween(1, 8),
            'description' => fake()->sentence(16),
        ];
    }
}

