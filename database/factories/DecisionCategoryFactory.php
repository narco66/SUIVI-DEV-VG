<?php

namespace Database\Factories;

use App\Models\DecisionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DecisionCategory>
 */
class DecisionCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Categorie ' . fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->bothify('CAT-###')),
            'description' => fake()->sentence(15),
        ];
    }
}

