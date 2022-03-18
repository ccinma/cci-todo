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
     * It should:
     *  - only accept logged users
     *  - only accept AJAX requests
     *  - return 400 when not a valid UUID
     *  - return 404 when workspace not found
     *  - return 401 when user not creator
     *  - delete and return 204 when user is workspace a member
     * 
     * @return void
     */
    public function testDeleteWorkspace()
    {
        $users = factory(User::class, 2)->create();

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0]->id
        ]);

        // NOT LOGGED
        $this->deleteJson('/workspace'.'/'.$workspace->id, [], $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->deleteJson('/workspace'.'/'.$workspace->id, [])->assertForbidden();

        // NOT UUID
        $this->actingAs($users[0]);
        $this->deleteJson('/workspace/notauuid', [], $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->actingAs($users[0]);
        $this->deleteJson('/workspace'.'/'.$this->faker()->uuid(), [], $this->ajaxHeader)->assertNotFound();

        // NOT CREATOR
        $this->actingAs($users[1]);
        $this->deleteJson('/workspace'.'/'.$workspace->id, [], $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST BY CREATOR
        $this->actingAs($users[0]);
        $request = $this->deleteJson('/workspace'.'/'.$workspace->id, [], $this->ajaxHeader);
        $request->assertStatus(204);
        $this->assertDatabaseMissing('workspaces', ['id' => $workspace->id]);

    }
}
