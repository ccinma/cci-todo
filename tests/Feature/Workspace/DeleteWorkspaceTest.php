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

        $workspaces = $this->generateWorkspaces($users[0], 1);

        // NOT LOGGED
        $this->deleteJson('/workspace'.'/'.$workspaces[0]->id, [], $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->deleteJson('/workspace'.'/'.$workspaces[0]->id, [])->assertForbidden();

        // NOT UUID
        $this->deleteJson('/workspace/notauuid', [], $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->deleteJson('/workspace'.'/'.$this->faker()->uuid(), [], $this->ajaxHeader)->assertNotFound();

        // NOT WORKSPACE MEMBER 
        $this->actingAs($users[1]);
        $this->deleteJson('/workspace'.'/'.$workspaces[0]->id, [], $this->ajaxHeader)->assertUnauthorized();

        // NOT ADMIN
        $workspaces[0]->addMember($users[1]);
        $this->deleteJson('/workspace'.'/'.$workspaces[0]->id, [], $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST BY ADMIN
        $workspaces[0]->setAdmin($users[1]);
        $request = $this->deleteJson('/workspace'.'/'.$workspaces[0]->id, [], $this->ajaxHeader);
        $request->assertStatus(204);
        $this->assertDatabaseMissing('workspaces', ['id' => $workspaces[0]->id]);

    }
}
