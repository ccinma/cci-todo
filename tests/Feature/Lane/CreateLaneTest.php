<?php

namespace Tests\Feature;

use App\Board;
use App\Lane;
use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateLaneTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - only accept logged users
     *  - only accept AJAX requests
     *  - return 422 when bad data
     *  - return 404 when not found board
     *  - return 401 when user not workspace member
     *  - create board and return it in 201 request when user is a workspace member
     * 
     * @return void
     */
    public function testCreateLane()
    {
        $users = factory(User::class, 2)->create();

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0]->id,
        ]);
        
        $board = factory(Board::class)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $users[0]->id,
        ]);

        $attributes = [
            'name' => $this->faker()->text(25),
            'board_id' => $board->id,
        ];


        // NOT LOGGED
        $this->postJson('/lane', $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->postJson('/lane', $attributes)->assertForbidden();
        
        // BAD DATA
        $this->actingAs($users[0]);
        $this->postJson('/lane', ['name' => 450], $this->ajaxHeader)->assertStatus(422);

        // BOARD NOT FOUND
        $this->actingAs($users[0]);
        $this->postJson('/lane', [
            'name' => 'Board not found',
            'board_id' => $this->faker()->uuid(),
        ], $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->postJson('/lane', $attributes, $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST
        $this->actingAs($users[1]);
        $board->workspace->addMember($users[1]);
        $response = $this->postJson('/lane', $attributes, $this->ajaxHeader);
        $response->assertCreated();
        $this->assertDatabaseHas('lanes', $attributes);
        $lane = Lane::where($attributes)->first();
        $response->assertJsonFragment([
            'id' => $lane->id,
        ]);

        // INCREMENTING BY CREATING A SECOND LANE
        $this->postJson('/lane', [
            'name' => 'Second Lane',
            'board_id' => $board->id,
        ], $this->ajaxHeader);
        $secondLane = Lane::where('name', 'Second Lane')->first();
        $this->assertEquals($lane->id, $secondLane->previous_id);



    }
}
