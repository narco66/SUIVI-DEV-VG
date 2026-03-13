<?php

namespace Tests\Feature;

use App\Models\DecisionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class DecisionAutosaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_autosave_decision_and_receive_id(): void
    {
        $user = User::factory()->create();
        $type = DecisionType::factory()->create();
        Permission::firstOrCreate(['name' => 'decisions.create']);
        $user->givePermissionTo('decisions.create');

        $response = $this->actingAs($user)->postJson(route('decisions.autosave'), [
            'title' => 'Brouillon auto',
            'type_id' => $type->id,
            'priority' => 3,
            'date_adoption' => now()->toDateString(),
        ]);

        $response->assertOk()->assertJson(['ok' => true]);
        $this->assertNotNull($response->json('decision_id'));

        $this->assertDatabaseHas('decisions', [
            'id' => $response->json('decision_id'),
            'title' => 'Brouillon auto',
            'status' => 'draft',
        ]);
    }
}
