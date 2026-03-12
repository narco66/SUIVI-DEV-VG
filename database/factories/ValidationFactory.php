<?php

namespace Database\Factories;

use App\Models\ProgressUpdate;
use App\Models\User;
use App\Models\Validation;
use App\Support\InstitutionalText;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Validation>
 */
class ValidationFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['approved', 'rejected']);

        return [
            'update_id' => ProgressUpdate::query()->inRandomOrder()->value('id') ?? ProgressUpdate::factory(),
            'validator_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'status' => $status,
            'level' => fake()->numberBetween(1, 3),
            'comment' => InstitutionalText::validationComment($status),
            'validated_at' => fake()->dateTimeBetween('-8 months', 'now'),
        ];
    }
}

