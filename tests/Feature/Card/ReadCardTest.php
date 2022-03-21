<?php

namespace Tests\Feature;

use App\Card;
use App\Lane;
use App\User;
use App\Board;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadCardTest extends TestCase
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
    public function testGetCard()
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

        $card = factory(Card::class)->create([
            'lane_id' => $lane->id,
            'user_id' => $users[0]->id,
        ]);


        // NOT LOGGED
        $this->getJson('/card'.'/'.$card->id, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->getJson('/card'.'/'.$card->id)->assertForbidden();
        
        // NOT UUID
        $this->getJson('/card/notUUID', $this->ajaxHeader)->assertStatus(400);
        
        // NOT FOUND
        $this->getJson('/card'.'/'.$this->faker()->uuid(), $this->ajaxHeader)->assertNotFound();
        
        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->getJson('/card'.'/'.$card->id, $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST
        $workspace->addMember($users[1]);
        $request = $this->getJson('/card'.'/'.$card->id, $this->ajaxHeader);
        $request->assertOk();
        $request->assertJsonFragment([
            'id' => $card->id,
        ]);


    }
}
