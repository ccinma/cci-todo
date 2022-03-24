<?php

namespace Tests\Feature;

use App\Board;
use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateBoardTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /**
     * It should:
     *  - only accept logged users
     *  - only accept AJAX requests
     *  - return 400 when not UUID
     *  - return 404 when board not found
     *  - return 401 when user not workspace member
     *  - return 304 when validated request is empty
     *  - return 200 AND the board when valid request
     * 
     * @return void
     */
    public function testUpdateBoard()
    {
        $users = factory(User::class, 2)->create();

        $workspace = $this->generateWorkspaces($users[0]);

        $board = factory(Board::class)->create([
            'user_id' => $users[0]->id,
            'workspace_id' => $workspace->id
        ]);

        $newAttributes = [
            'name' => 'Nouveau nom',
        ];

        // NOT LOGGED
        $this->putJson('/board'.'/'.$board->id, $newAttributes, $this->ajaxHeader)->assertUnauthorized();
        
        // NOT AJAX
        $this->actingAs($users[0]);
        $this->putJson('/board'.'/'.$board->id, $newAttributes)->assertForbidden();
        
        // NOT UUID
        $this->putJson('/board/notUUID', $newAttributes, $this->ajaxHeader)->assertStatus(400);
        
        // NOT FOUND
        $this->putJson('/board'.'/'.$this->faker()->uuid(), $newAttributes, $this->ajaxHeader)->assertNotFound();

        // LOGGED WITH NON AUTHORIZED USER
        $this->actingAs($users[1]);
        $this->putJson('/board'.'/'.$board->id, [], $this->ajaxHeader)->assertUnauthorized();
        $this->putJson('/board'.'/'.$board->id, $newAttributes, $this->ajaxHeader)->assertUnauthorized();

        // LOGGED WITH MEMBER USER
        $this->actingAs($users[1]);
        $workspace->addMember($users[1]);
        $this->putJson('/board'.'/'.$board->id, [], $this->ajaxHeader)->assertStatus(304);
        $request = $this->putJson('/board'.'/'.$board->id, $newAttributes, $this->ajaxHeader);
        $request->assertOk();
        $this->assertDatabaseHas('boards', $newAttributes);
        $updatedBoard = Board::find($board->id);
        $request->assertJsonFragment([
            'id' => $updatedBoard->id
        ]);

    }
}
