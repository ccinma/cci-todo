<?php

namespace Tests\Feature;

use App\Board;
use App\Lane;
use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateLaneTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 AJAX requests
     *  - return 400 when not UUID
     *  - return 404 when lane not found
     *  - return 422 when bad data request
     *  - return 401 when user not workspace member
     *  - return 304 when validated request is empty
     *  - return 200 AND the lane when valid request
     * 
     * @return void
     */
    public function testUpdateBoard()
    {
        $users = factory(User::class, 2)->create();

        $workspace = $this->generateWorkspaces($users[0]);

        $board = factory(Board::class)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $users[0]->id,
        ]);

        $lane = factory(Lane::class)->create([
            'board_id' => $board->id,
            'user_id' => $users[0]->id
        ]);

        $attributes = [
            'name' => 'Name changed',
        ];

        // NOT LOGGED
        $this->putJson('/lane'.'/'.$lane->id, $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->putJson('/lane'.'/'.$lane->id, $attributes)->assertForbidden();

        // NOT UUID
        $this->actingAs($users[0]);
        $this->putJson('/lane/notuuid', $attributes, $this->ajaxHeader)->assertStatus(400);

        // LANE NOT FOUND
        $this->actingAs($users[0]);
        $this->putJson('/lane'.'/'.$this->faker()->uuid(), $attributes, $this->ajaxHeader)->assertNotFound();

        // BAD REQUEST
        $this->actingAs($users[0]);
        $this->putJson('/lane'.'/'.$lane->id, ['name' => 420], $this->ajaxHeader)->assertStatus(422);

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->putJson('/lane'.'/'.$lane->id, $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NO CHANGES
        $this->actingAs($users[0]);
        $this->putJson('/lane'.'/'.$lane->id, [], $this->ajaxHeader)->assertStatus(304);

        // VALID REQUEST
        $this->actingAs($users[1]);
        $workspace->addMember($users[1]);
        $request = $this->putJson('/lane'.'/'.$lane->id, $attributes, $this->ajaxHeader);
        $request->assertOk();
        $this->assertDatabaseHas('lanes', $attributes);
        $request->assertJsonFragment($attributes);
    }
}
