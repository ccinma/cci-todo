<?php

namespace Tests\Feature;

use App\User;
use App\Board;
use App\Label;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteLabelTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 if not AJAX request
     *  - return 400 if not UUID
     *  - return 404 when not found
     *  - return 401 when not authorized
     *  - delete and return 204 when valid request
     * 
     * @return void
     */
    public function testDeleteLabel()
    {
        $users = factory(User::class, 2)->create();

        $workspace = factory(Workspace::class)->create([
            'user_id' => $users[0]->id,
        ]);

        $board = factory(Board::class)->create([
            'workspace_id' => $workspace->id,
            'user_id' => $users[0]->id,
        ]);

        $label = factory(Label::class)->create([
            'board_id' => $board->id,
            'user_id' => $users[0]->id, 
        ]);

        // NOT LOGGED
        $this->deleteJson('/label'.'/'.$label->id, [], $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->deleteJson('/label'.'/'.$label->id, [])->assertForbidden();

        // UUID
        $this->deleteJson('/label/notUUID', [], $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->deleteJson('/label'.'/'.$this->faker()->uuid(), [], $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->deleteJson('/label'.'/'.$label->id, [], $this->ajaxHeader)->assertUnauthorized();

        // VALID REQUEST
        $workspace->addMember($users[1]);
        $response = $this->deleteJson('/label'.'/'.$label->id, [], $this->ajaxHeader);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('labels', $label->toArray());
        

    }
}
