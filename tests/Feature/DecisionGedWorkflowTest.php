<?php

namespace Tests\Feature;

use App\Models\Decision;
use App\Models\DecisionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DecisionGedWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_decision_draft_with_document(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $type = DecisionType::factory()->create();

        $payload = [
            'form_action' => 'draft',
            'title' => 'Décision test de création',
            'type_id' => $type->id,
            'priority' => 3,
            'date_adoption' => now()->toDateString(),
            'documents' => [
                UploadedFile::fake()->create('acte-test.pdf', 120, 'application/pdf'),
            ],
            'document_meta' => [
                [
                    'title' => 'Acte signé',
                    'category' => 'acte_signe',
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(route('decisions.store'), $payload);

        $response->assertRedirect();

        $this->assertDatabaseHas('decisions', [
            'title' => 'Décision test de création',
            'status' => 'draft',
        ]);

        $decision = Decision::where('title', 'Décision test de création')->firstOrFail();

        $this->assertDatabaseHas('documents', [
            'decision_id' => $decision->id,
            'title' => 'Acte signé',
        ]);
    }
}

