<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Domain>
 */
class DomainFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Domaine ' . fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->bothify('DOM-###')),
            'description' => fake()->sentence(16),
            'parent_id' => null,
        ];
    }
}

