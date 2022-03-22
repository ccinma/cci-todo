<?php

namespace Tests\Feature;

use App\Card;
use App\Lane;
use App\User;
use App\Board;
use App\Label;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateLabelTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 AJAX requests
     *  - return 400 when not UUID
     *  - return 404 when not found
     *  - return 422 when bad data request
     *  - return 401 when user not workspace member
     *  - return 304 when validated request is empty
     *  - return 200 AND the card when valid request
     * 
     * @return void
     */
    public function testUpdateLabel()
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

        $label = factory(Label::class)->create([
            'board_id' => $board->id,
            'user_id' => $users[0]->id, 
        ]);

        $attributes = [
            'name' => 'Name changed',
            'color' => '#FFFFFF',
        ];


        // NOT LOGGED
        $this->putJson('/label'.'/'.$label->id, $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->putJson('/label'.'/'.$label->id, $attributes)->assertForbidden();

        // UUID
        $this->putJson('/label/notUUID', $attributes, $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->putJson('/label'.'/'.$this->faker()->uuid(), $attributes, $this->ajaxHeader)->assertNotFound();
        
        // BAD DATA
        $this->putJson('/label'.'/'.$label->id, [
            'name' => 420
        ], $this->ajaxHeader)->assertStatus(422);
        $this->putJson('/label'.'/'.$label->id, [
            'color' => 'FFFFFF'
        ], $this->ajaxHeader)->assertStatus(422);
        $this->putJson('/label'.'/'.$label->id, [
            'color' => 'FFF'
        ], $this->ajaxHeader)->assertStatus(422);
        
        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->putJson('/label'.'/'.$label->id, $attributes, $this->ajaxHeader)->assertUnauthorized();
        
        // EMPTY REQUEST BODY
        $this->actingAs($users[0]);
        $this->putJson('/label'.'/'.$label->id, [], $this->ajaxHeader)->assertStatus(304);

        // VALID REQUEST
        $this->actingAs($users[1]);
        $workspace->addMember($users[1]);
        $response = $this->putJson('/label'.'/'.$label->id, $attributes, $this->ajaxHeader);
        $response->assertOk();
        $this->assertDatabaseHas('labels', $attributes);
        $response->assertJsonFragment([
            'id' => $label->id,
        ]);
    }
}
