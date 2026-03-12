<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\ProgressUpdate;
use App\Models\User;
use App\Support\InstitutionalText;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgressUpdate>
 */
class ProgressUpdateFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'validated', 'rejected']);

        return [
            'updatable_type' => Action::class,
            'updatable_id' => Action::query()->inRandomOrder()->value('id') ?? Action::factory(),
            'progress_rate' => fake()->numberBetween(5, 100),
            'achievements' => InstitutionalText::achievement(),
            'difficulties' => InstitutionalText::difficulty(),
            'next_steps' => InstitutionalText::nextStep(),
            'support_needs' => InstitutionalText::supportNeed(),
            'status' => $status,
            'author_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'submitted_at' => fake()->dateTimeBetween('-9 months', 'now'),
        ];
    }
}

