<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'institution_id' => Institution::query()->inRandomOrder()->value('id') ?? Institution::factory(),
            'name' => 'Departement ' . fake()->unique()->company(),
            'code' => strtoupper(fake()->unique()->bothify('DEP-###')),
            'commissaire_id' => null,
        ];
    }
}

