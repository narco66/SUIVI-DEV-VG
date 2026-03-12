<?php

namespace Database\Factories;

use App\Models\DecisionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DecisionType>
 */
class DecisionTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Type ' . fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->bothify('DT-###')),
            'description' => fake()->sentence(14),
        ];
    }
}

