<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->country(),
            'code_iso' => strtoupper(fake()->unique()->lexify('???')),
            'flag_url' => fake()->optional()->imageUrl(640, 480, 'flags'),
            'region' => fake()->randomElement(['Afrique Centrale', 'Afrique de l Ouest', 'Afrique de l Est']),
        ];
    }
}
