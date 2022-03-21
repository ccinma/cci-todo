<?php

namespace Tests\Feature;

use App\Board;
use App\Lane;
use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadLaneTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 when not ajax
     *  - return 400 when not UUID
     *  - return 404 when not found
     *  - return 401 when not authorized
     *  - return 200 and data when authorized
     *
     * @return void
     */
    public function testReadOneLane()
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
            'user_id' => $users[0]->id,
        ]);

        // NOT LOGGED
        $this->getJson('/lane'.'/'.$lane->id, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->getJson('/lane'.'/'.$lane->id)->assertForbidden();

        // NOT UUID
        $this->actingAs($users[0]);
        $this->getJson('/lane/notUUID', $this->ajaxHeader)->assertStatus(400);

        // LANE NOT FOUND
        $this->actingAs($users[0]);
        $this->getJson('/lane'.'/'.$this->faker()->uuid(), $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->getJson('/lane'.'/'.$lane->id, $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST
        $this->actingAs($users[1]);
        $workspace->addMember($users[1]);
        $request = $this->getJson('/lane'.'/'.$lane->id, $this->ajaxHeader);
        $request->assertOk();
        $lane = Lane::find($lane->id);
        $request->assertJsonFragment([
            'id' => $lane->id,
        ]);

    }
}
