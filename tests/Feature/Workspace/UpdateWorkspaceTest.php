<?php

namespace Tests\Feature;

use App\User;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateWorkspaceTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - only accept logged users
     *  - only accept AJAX requests
     *  - return 404 when workspace not found
     *  - return 401 when user is not the creator
     *  - return 304 when validated request is empty
     *  - return 200 AND the workspace if the request is valid
     * 
     * @return void
     */
    public function testChangeWorkspaceProperties()
    {
        $users = factory(User::class, 2)->create();

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0]->id,
        ]);

        $attributes = [
            'name' => 'Changed name',
        ];

        // NOT LOGGED
        $this->putJson('/workspace'.'/'.$workspace->id, $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->putJson('/workspace'.'/'.$workspace->id, $attributes)->assertForbidden();

        // NOT UUID
        $this->actingAs($users[0]);
        $this->putJson('/workspace/2', $attributes, $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->actingAs($users[0]);
        $this->putJson('/workspace'.'/'.$this->faker()->uuid(), $attributes, $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->putJson('/workspace'.'/'.$workspace->id, $attributes, $this->ajaxHeader)->assertUnauthorized();

        // EMPTY REQUEST BODY
        $this->actingAs($users[0]);
        $this->putJson('/workspace'.'/'.$workspace->id, [], $this->ajaxHeader)->assertStatus(304);

        // VALID REQUEST
        $this->actingAs($users[0]);
        $request = $this->putJson('/workspace'.'/'.$workspace->id, $attributes, $this->ajaxHeader);
        $request->assertOk();
        $this->assertDatabaseHas('workspaces', $attributes);
        $updatedWorkspace = Workspace::find($workspace->id);
        $request->assertJsonFragment([
            'data' => $updatedWorkspace->toArray(),
        ]);
    }
}
