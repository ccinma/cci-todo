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
     * It should show every board of a membered workspace, and only for this workspace.
     *
     * @return void
     */
    public function testGetWorskpaceBoards()
    {
        $users = factory(User::class, 2)->create();
        $this->actingAs($users[0]);

        $workspaces = factory(Workspace::class, 1)->create([
            'user_id' => $users[0]->id
        ])->merge(
            factory(Workspace::class, 1)->create([
                'user_id' => $users[1]->id
            ])
        );

        factory(Board::class, 3)->create([
            'user_id' => $users[0]->id,
            'workspace_id' => $workspaces[0]->id,
        ]);

        factory(Board::class, 3)->create([
            'user_id' => $users[1]->id,
            'workspace_id' => $workspaces[1]->id,
        ]);

        foreach($workspaces[0]->boards as $board) {
            $this->get('/workspace'.'/'.$workspaces[0]->id)->assertSee($board->name);
        }
        foreach($workspaces[1]->boards as $board) {
            $this->get('/workspace'.'/'.$workspaces[0]->id)->assertDontSee($board->name);
        }
        foreach($workspaces[1]->boards as $board) {
            $this->get('/workspace'.'/'.$workspaces[1]->id)->assertDontSee($board->name);
        }
    }

    /**
     * It should display the board or not if the user is not authorized to
     */
    public function testBoardPage()
    {
        $this->withoutExceptionHandling();

        $users = factory(User::class, 2)->create();
        $this->actingAs($users[0]);

        $workspaces = factory(Workspace::class, 1)->create([
            'user_id' => $users[0]->id
        ])->merge(
            factory(Workspace::class, 1)->create([
                'user_id' => $users[1]->id
            ])
        );

        $boards = factory(Board::class, 2)->create([
            'user_id' => $users[0]->id,
            'workspace_id' => $workspaces[0]->id,
        ])->merge(
            factory(Board::class, 1)->create([
                'user_id' => $users[1]->id,
                'workspace_id' => $workspaces[1]->id,
            ])
        );

        $this->get('/board'.'/'.$boards[0]->id)->assertSee($boards[0]->name);
        $this->get('/board'.'/'.$boards[0]->id)->assertDontSee($boards[1]->name);
        $this->get('/board'.'/'.$boards[1]->id)->assertSee($boards[1]->name);
        $this->get('/board'.'/'.$boards[2]->id)->assertDontSee($boards[2]->name);
    }
}
