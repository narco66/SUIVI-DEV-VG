<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Milestone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Milestone>
 */
class MilestoneFactory extends Factory
{
    public function definition(): array
    {
        $expected = fake()->dateTimeBetween('-6 months', '+4 months');
        $status = fake()->randomElement(['pending', 'in_progress', 'completed', 'delayed', 'blocked', 'validated', 'rejected']);
        $achieved = in_array($status, ['completed', 'validated'], true)
            ? (clone $expected)->modify('+' . fake()->numberBetween(-10, 25) . ' days')
            : null;

        return [
            'activity_id' => Activity::query()->inRandomOrder()->value('id') ?? Activity::factory(),
            'title' => 'Jalon : ' . fake()->sentence(3),
            'expected_date' => $expected->format('Y-m-d'),
            'achieved_date' => $achieved?->format('Y-m-d'),
            'status' => $status,
        ];
    }
}

