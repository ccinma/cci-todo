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

class CreateLabelTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 when not AJAX request
     *  - return 422 when bad data
     *  - return 404 when not found board
     *  - return 401 when user not workspace member
     *  - create board and return it in 201 request when user is a workspace member
     * 
     * @return void
     */
    public function testExample()
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
            'board_id' => $board->id,
            'name' => $this->faker()->text(20),
            'color' => $this->faker()->hexColor(),
        ];

        // NOT LOGGED
        $this->postJson('/label', $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->postJson('/label', $attributes)->assertForbidden();

        // BAD DATA REQUEST
        $this->postJson('/label', [
            'board_id' => 3,
            'name' => $this->faker()->text(20),
            'color' => $this->faker()->hexColor(),
        ], $this->ajaxHeader)->assertStatus(422);

        $this->postJson('/label', [
            'board_id' => "3",
            'name' => $this->faker()->text(20),
            'color' => $this->faker()->hexColor(),
        ], $this->ajaxHeader)->assertStatus(422);

        $this->postJson('/label', [
            'board_id' => $this->faker()->uuid(),
            'name' => 3,
            'color' => $this->faker()->hexColor(),
        ], $this->ajaxHeader)->assertStatus(422);

        $this->postJson('/label', [
            'board_id' => $this->faker()->uuid(),
            'name' => $this->faker()->text(300),
            'color' => $this->faker()->hexColor(),
        ], $this->ajaxHeader)->assertStatus(422);

        $this->postJson('/label', [
            'board_id' => $this->faker()->uuid(),
            'name' => $this->faker()->text(20),
            'color' => 35,
        ], $this->ajaxHeader)->assertStatus(422);

        $this->postJson('/label', [
            'board_id' => $this->faker()->uuid(),
            'name' => $this->faker()->text(20),
            'color' => "35",
        ], $this->ajaxHeader)->assertStatus(422);


        // BOARD NOT FOUND
        $this->postJson('/label', [
            'board_id' => $this->faker()->uuid(),
            'name' => $this->faker()->text(20),
            'color' => $this->faker()->hexColor(),
        ], $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->postJson('/label', $attributes, $this->ajaxHeader)->assertUnauthorized();
        
        // VALID REQUEST
        $workspace->addMember($users[1]);
        $request = $this->postJson('/label', $attributes, $this->ajaxHeader);
        $request->assertCreated();
        $this->assertDatabaseHas('labels', $attributes);
    }
}
