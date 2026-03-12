<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\ActorAssignment;
use App\Models\Country;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActorAssignment>
 */
class ActorAssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'assignable_type' => Action::class,
            'assignable_id' => Action::query()->inRandomOrder()->value('id') ?? Action::factory(),
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'institution_id' => Institution::query()->inRandomOrder()->value('id'),
            'country_id' => Country::query()->inRandomOrder()->value('id'),
            'type' => fake()->randomElement(['responsable', 'point_focal', 'validateur']),
            'start_date' => fake()->dateTimeBetween('-9 months', '-1 month')->format('Y-m-d'),
            'end_date' => fake()->optional(0.3)->dateTimeBetween('+1 month', '+12 months')?->format('Y-m-d'),
        ];
    }
}

