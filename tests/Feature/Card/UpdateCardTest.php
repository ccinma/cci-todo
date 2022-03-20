<?php

namespace Tests\Feature;

use App\Board;
use App\Card;
use App\Lane;
use App\User;
use App\Workspace;
use Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateCardTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 AJAX requests
     *  - return 400 when not UUID
     *  - return 404 when card not found
     *  - return 422 when bad data request
     *  - return 401 when user not workspace member
     *  - return 304 when validated request is empty
     *  - return 200 AND the card when valid request
     * 
     * @return void
     */
    public function testUpdateCard()
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

        $attributes = [
            'name' => 'Name changed',
        ];

        // NOT LOGGED
        $this->putJson('/card'.'/'.$card->id, $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->putJson('/card'.'/'.$card->id, $attributes)->assertForbidden();

        // NOT UUID
        $this->putJson('/card'.'/'.'NOTUUID', $attributes, $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->putJson('/card'.'/'.$this->faker()->uuid(), $attributes, $this->ajaxHeader)->assertNotFound();

        // BAD REQUEST / BAD DATA
        $this->putJson('/card'.'/'.$card->id, ['name' => 420], $this->ajaxHeader)->assertStatus(422);

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->putJson('/card'.'/'.$card->id, $attributes, $this->ajaxHeader)->assertUnauthorized();

        // EMPTY REQUEST
        $workspace->addMember($users[1]);
        $this->putJson('/card'.'/'.$card->id, [], $this->ajaxHeader)->assertStatus(304);

        // VALID REQUEST
        $request = $this->putJson('/card'.'/'.$card->id, $attributes, $this->ajaxHeader);
        $request->assertOk();
        $this->assertDatabaseHas('cards', $attributes);
        $request->assertJsonFragment($attributes);
    }
}
