<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'action' => fake()->randomElement(['created', 'updated', 'validated', 'rejected', 'closed']),
            'auditable_type' => Action::class,
            'auditable_id' => Action::query()->inRandomOrder()->value('id'),
            'old_values' => [
                'status' => fake()->randomElement(['planned', 'in_progress', 'delayed']),
                'progress_rate' => fake()->numberBetween(10, 70),
            ],
            'new_values' => [
                'status' => fake()->randomElement(['in_progress', 'completed', 'blocked']),
                'progress_rate' => fake()->numberBetween(25, 100),
            ],
            'ip_address' => fake()->ipv4(),
            'created_at' => fake()->dateTimeBetween('-8 months', 'now'),
            'updated_at' => now(),
        ];
    }
}

