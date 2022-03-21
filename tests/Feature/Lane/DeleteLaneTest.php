<?php

namespace Tests\Feature;

use App\Lane;
use App\User;
use App\Board;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteLaneTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 if not AJAX request
     *  - return 400 if not UUID
     *  - return 404 when lane not found
     *  - return 401 when unauthorized (not workspace member)
     *  - delete and return 204 when valid request
     * 
     * @return void
     */
    public function testDeleteLane()
    {
        $users = factory(User::class, 2)->create();

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0]->id,
        ]);

        $board = factory(Board::class)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $users[0]->id,
        ]);

        $lane = factory(Lane::class)->create([
            'board_id' => $board->id,
            'user_id' => $users[0]->id
        ]);
        
        // NOT LOGGED
        $this->deleteJson('/lane'.'/'.$lane->id, [], $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->deleteJson('/lane'.'/'.$lane->id, [])->assertForbidden();

        // NOT UUID
        $this->actingAs($users[0]);
        $this->deleteJson('/lane/notUUID', [], $this->ajaxHeader)->assertStatus(400);

        // LANE NOT FOUND
        $this->actingAs($users[0]);
        $this->deleteJson('/lane'.'/'.$this->faker()->uuid(), [], $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->deleteJson('/lane'.'/'.$lane->id, [], $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST
        $this->actingAs($users[1]);
        $workspace->addMember($users[1]);
        $request = $this->deleteJson('/lane'.'/'.$lane->id, [], $this->ajaxHeader);
        $request->assertStatus(204);
        $this->assertDatabaseMissing('lanes', $lane->toArray());
    }
}
