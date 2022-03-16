<?php

namespace Tests\Feature;

use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteWorkspaceTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDeleteWorkspace()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $workspace = factory(Workspace::class)->create([
            'user_id' => $user->id,
        ]);

        $this->deleteJson('/workspace'.'/'.$workspace->id, [], $this->ajaxHeader)->assertOk();
        $this->deleteJson('/workspace'.'/'.$workspace->id, [])->assertForbidden();

        $this->assertDatabaseMissing('workspaces', $workspace->toArray());
    }
}
