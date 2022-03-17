<?php

namespace Tests\Feature;

use App\Board;
use App\Traits\Uuids;
use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateBoardTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - only accept logged users
     *  - only accept AJAX requests
     *  - return 401 when user not workspace member
     *  - create board and return it in 201 request when user is a workspace member
     * 
     * @return void
     */
    public function testCreateBoard()
    {
        $users = factory(User::class, 2)->create();

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0],
        ]);

        $inexistantID = [
            'name' => $this->faker()->text(25),
            'workspace_id' => $this->faker()->uuid(),
        ];

        $attributes = [
            'name' => $this->faker()->text(25),
            'workspace_id' => $workspace->id,
        ];

        // NOT LOGGED
        $this->postJson('/board', $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->postJson('/board', $attributes)->assertForbidden();

        // NOT FOUND WORKSPACE
        $this->actingAs($users[0]);
        $this->postJson('/board', $inexistantID, $this->ajaxHeader)->assertNotFound();

        // NOT WORKSPACE MEMBER
        $this->actingAs($users[1]);
        $this->postJson('/board', $attributes, $this->ajaxHeader)->assertUnauthorized();

        // WORKSPACE MEMBER
        $this->actingAs($users[1]);
        $workspace->addMember($users[1]);
        $request = $this->postJson('/board', $attributes, $this->ajaxHeader);
        $this->assertDatabaseHas('boards', $attributes);
        $request->assertCreated();
        $insertedBoard = Board::where($attributes)->firstOrFail();
        $request->assertJsonFragment([
            'data' => $insertedBoard->toArray(),
        ]);
    }
}
