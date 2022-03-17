<?php

namespace Tests\Feature;

use App\User;
use App\Board;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteBoardTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * It should:
     *  - only accept logged users
     *  - only accept AJAX requests
     *  - return 404 when board not found
     *  - return 401 when user not workspace member
     *  - delete and return 204 when user is workspace a member
     * 
     * @return void
     */
    public function testDeleteBoard()
    {
        $users = factory(User::class, 3)->create();

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0]->id
        ]);

        $board = factory(Board::class)->create([
            'user_id' => $users[0]->id,
            'workspace_id' => $workspace->id
        ]);

        // NOT LOGGED
        $this->deleteJson('/board'.'/'.$board->id)->assertUnauthorized();
        $this->deleteJson('/board'.'/'.$board->id, [], $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->deleteJson('/board'.'/'.$board->id)->assertForbidden();

        // NOT FOUND
        $this->actingAs($users[0]);
        $this->deleteJson('/board/idontexists', [], $this->ajaxHeader)->assertNotFound();

        // NOT WORKSPACE MEMBER
        $this->actingAs($users[1]);
        $this->deleteJson('/board'.'/'.$board->id, [], $this->ajaxHeader)->assertUnauthorized();

        // WORKSPACE MEMBER
        $this->actingAs($users[1]);
        $workspace->addMember($users[1]);
        $this->deleteJson('/board'.'/'.$board->id, [], $this->ajaxHeader)->assertNoContent();
        $this->assertDatabaseMissing('boards', $board->toArray());
    }
}
