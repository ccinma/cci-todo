<?php

namespace Tests\Feature;

use App\Board;
use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadBoardTest extends TestCase
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
    public function testReadBoard()
    {
        $users = factory(User::class, 2)->create();

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0]->id,
        ]);

        $board = factory(Board::class)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $users[0]->id,
        ]);


        // NOT LOGGED
        $this->getJson('/board'.'/'.$board->id, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->getJson('/board'.'/'.$board->id)->assertForbidden();

        // NOT UUID
        $this->getJson('/board/notUUID', $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->getJson('/board'.'/'.$this->faker()->uuid(), $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->getJson('/board'.'/'.$board->id, $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST
        $workspace->addMember($users[1]);
        $response = $this->getJson('/board'.'/'.$board->id, $this->ajaxHeader);
        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $board->id
        ]);

    }
}
