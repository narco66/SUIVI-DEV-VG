<?php

namespace Database\Factories;

use App\Models\Organ;
use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Session>
 */
class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-18 months', '+2 months');
        $end = (clone $start)->modify('+' . fake()->numberBetween(1, 4) . ' days');

        return [
            'organ_id' => Organ::query()->inRandomOrder()->value('id') ?? Organ::factory(),
            'code' => 'SES-' . fake()->unique()->numerify('####'),
            'type' => fake()->randomElement(['Ordinaire', 'Extraordinaire']),
            'date_start' => $start->format('Y-m-d'),
            'date_end' => $end->format('Y-m-d'),
            'location' => fake()->randomElement(['Libreville', 'Brazzaville', 'Yaounde', 'Ndjamena', 'Malabo', 'Kigali']),
            'status' => fake()->randomElement(['planned', 'ongoing', 'closed']),
        ];
    }
}

