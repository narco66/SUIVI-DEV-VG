<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Institution>
 */
class InstitutionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Institution ' . fake()->unique()->company(),
            'code' => strtoupper(fake()->unique()->bothify('INST-###')),
            'type_id' => fake()->randomElement(['ceeac', 'etat_membre', 'partenaire']),
            'country_id' => Country::query()->inRandomOrder()->value('id') ?? Country::factory(),
        ];
    }
}

