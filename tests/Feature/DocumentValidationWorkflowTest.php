<?php

namespace Tests\Feature;

use App\Models\Decision;
use App\Models\DecisionType;
use App\Models\Document;
use App\Models\DocumentValidationFlow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DocumentValidationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_flow_can_be_started_for_document(): void
    {
        $user = User::factory()->create();
        Role::firstOrCreate(['name' => 'point_focal']);
        Permission::firstOrCreate(['name' => 'documents.upload']);
        $user->assignRole('point_focal');
        $user->givePermissionTo('documents.upload');

        $decision = Decision::factory()->create([
            'type_id' => DecisionType::factory(),
            'created_by' => $user->id,
            'status' => 'draft',
            'date_adoption' => now()->toDateString(),
        ]);

        $document = Document::create([
            'documentable_type' => Decision::class,
            'documentable_id' => $decision->id,
            'decision_id' => $decision->id,
            'title' => 'Doc validation',
            'type' => 'pdf',
            'path' => 'ged/test/doc-validation.pdf',
            'storage_disk' => 'local',
            'size' => 12345,
            'uploader_id' => $user->id,
            'uploaded_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('ged.validations.start', $document));

        $response->assertRedirect();
        $this->assertDatabaseHas('document_validation_flows', [
            'document_id' => $document->id,
            'status' => 'in_review',
        ]);

        $flow = DocumentValidationFlow::where('document_id', $document->id)->first();
        $this->assertNotNull($flow);
        $this->assertDatabaseCount('document_validation_steps', 3);
    }
}
