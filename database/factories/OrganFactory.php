<?php

namespace Database\Factories;

use App\Models\Organ;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organ>
 */
class OrganFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Organe ' . fake()->unique()->company(),
            'code' => strtoupper(fake()->unique()->bothify('ORG-##')),
            'level' => fake()->numberBetween(1, 3),
            'parent_id' => null,
        ];
    }
}

