<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Deliverable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Deliverable>
 */
class DeliverableFactory extends Factory
{
    public function definition(): array
    {
        return [
            'activity_id' => Activity::query()->inRandomOrder()->value('id') ?? Activity::factory(),
            'title' => 'Livrable : ' . fake()->sentence(3),
            'description' => fake()->sentence(16),
            'expected_date' => fake()->dateTimeBetween('-4 months', '+6 months')->format('Y-m-d'),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'delayed', 'validated', 'rejected']),
        ];
    }
}

