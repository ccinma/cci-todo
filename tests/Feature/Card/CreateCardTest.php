<?php

namespace Tests\Feature;

use App\Board;
use App\Card;
use App\Lane;
use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCardTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 when not AJAX request
     *  - return 422 when bad data
     *  - return 404 when not found lane
     *  - return 401 when user not workspace member
     *  - create card and return it in 201 request when user is a workspace member
     *  - auto set previous and next when adding a new card
     * 
     * @return void
     */
    public function testCreateCard()
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

        $attributes = [
            'name' => $this->faker()->text(255),
            'description' => $this->faker()->text(1000),
            'lane_id' => $lane->id,
        ];

        // NOT LOGGED
        $this->postJson('/card', $attributes, $this->ajaxHeader)->assertUnauthorized();
        
        // NOT AJAX
        $this->actingAs($users[0]);
        $this->postJson('/card', $attributes)->assertForbidden();

        // BAD DATA
        $this->postJson('/card', [], $this->ajaxHeader)->assertStatus(422);
        $this->postJson('/card', ['name' => 255], $this->ajaxHeader)->assertStatus(422);
        $this->postJson('/card', ['name' => 255, 'description' => $attributes['description']], $this->ajaxHeader)->assertStatus(422);
        $this->postJson('/card', ['name' => 'More than 5000 chars', 'description' => $this->faker()->text(6000)], $this->ajaxHeader)->assertStatus(422);
        $this->postJson('/card', ['name' => 'Not UUID', 'description' => $this->faker()->text(5000), 'lane_id' => 'Hello'], $this->ajaxHeader)->assertStatus(422);

        // LANE NOT FOUND
        $this->postJson('/card', ['name' => 'UUID not found', 'lane_id' => $this->faker()->uuid()], $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->postJson('/card', $attributes, $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST
        $workspace->addMember($users[1]);
        $request = $this->postJson('/card', $attributes, $this->ajaxHeader);
        $request->assertCreated();
        $this->assertDatabaseHas('cards', $attributes);
        $request->assertJsonFragment($attributes);

        // CREATE A SECOND CARD
        $this->postJson('/card', [
            'name' => 'Second card',
            'lane_id' => $lane->id,
        ], $this->ajaxHeader);

        $cards = Card::orderBy('createdAt')->get();

        $this->assertEquals($cards[1]->id, $cards[0]->next_id);
        $this->assertEquals($cards[0]->id, $cards[1]->previous_id);
        
    }
}
