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

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0]->id
        ]);

        $board = factory(Board::class)->create([
            'user_id' => $users[0]->id,
            'workspace_id' => $workspace->id
        ]);

        $newAttributes = [
            'name' => 'Nouveau nom',
        ];

        // NOT LOGGED
        $this->putJson('/board'.'/'.$board->id, [])->assertUnauthorized();
        $this->putJson('/board'.'/'.$board->id, $newAttributes)->assertUnauthorized();
        
        // NOT AJAX
        $this->actingAs($users[0]);
        $this->putJson('/board'.'/'.$board->id, [])->assertForbidden();
        $this->putJson('/board'.'/'.$board->id, $newAttributes)->assertForbidden();
        
        // NOT FOUND
        $this->actingAs($users[0]);
        $this->putJson('/board/2', [], $this->ajaxHeader)->assertNotFound();
        $this->putJson('/board/2', $newAttributes, $this->ajaxHeader)->assertNotFound();

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
        $updatedBoard = Board::with(['workspace', 'workspace.members'])->find($board->id);
        $request->assertJsonFragment([
            'data' => $updatedBoard->toArray()
        ]);

    }
}
