<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['pdf', 'docx', 'xlsx', 'png']);
        $category = fake()->randomElement([
            'justificatif',
            'rapport',
            'acte',
            'compte_rendu',
            'note_technique',
        ]);

        return [
            'documentable_type' => Action::class,
            'documentable_id' => Action::query()->inRandomOrder()->value('id') ?? Action::factory(),
            'title' => fake()->randomElement([
                'Rapport trimestriel d execution',
                'Note de cadrage technique',
                'Proces-verbal de reunion',
                'Annexe des indicateurs de performance',
                'Justificatif de realisation terrain',
            ]),
            'type' => $type,
            'category' => $category,
            'path' => 'documents/demo/' . fake()->uuid() . '.' . $type,
            'size' => fake()->numberBetween(120, 9500),
            'uploader_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'uploaded_at' => fake()->dateTimeBetween('-12 months', 'now'),
        ];
    }
}

