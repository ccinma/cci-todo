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

class DeleteCardTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 if not AJAX request
     *  - return 400 if not UUID
     *  - return 404 when card not found
     *  - return 401 when unauthorized (not workspace member)
     *  - delete and return 204 when valid request
     * 
     * @return void
     */
    public function testDeleteCard()
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

        $cards = $this->generateCards($users[0], $lane, 3);
        
        // NOT LOGGED
        $this->deleteJson('/card'.'/'.$cards[1]->id, [], $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->deleteJson('/card'.'/'.$cards[1]->id, [])->assertForbidden();

        // NOT UUID
        $this->deleteJson('/card/notUUID', [], $this->ajaxHeader)->assertStatus(400);
        
        // CARD NOT FOUND
        $this->deleteJson('/card'.'/'.$this->faker()->uuid(), [], $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->deleteJson('/card'.'/'.$cards[1]->id, [], $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST
        $workspace->addMember($users[1]);
        $request = $this->deleteJson('/card'.'/'.$cards[1]->id, [], $this->ajaxHeader);
        $request->assertStatus(204);
        $this->assertDatabaseMissing('cards', $cards[1]->toArray());

        $previous = Card::find($cards[0]->id);
        $next = Card::find($cards[2]->id);

        $this->assertEquals($next->previous_id, $previous->id);
        $this->assertEquals($previous->next_id, $next->id);
    }
}
