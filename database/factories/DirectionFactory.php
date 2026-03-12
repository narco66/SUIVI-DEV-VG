<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Direction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Direction>
 */
class DirectionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'department_id' => Department::query()->inRandomOrder()->value('id') ?? Department::factory(),
            'name' => 'Direction ' . fake()->unique()->company(),
            'code' => strtoupper(fake()->unique()->bothify('DIR-###')),
            'director_id' => null,
        ];
    }
}

