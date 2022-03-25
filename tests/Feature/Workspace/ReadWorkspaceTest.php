<?php

namespace Tests\Feature;

use App;
use App\User;
use App\Board;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadWorkspaceTest extends TestCase
{
    use WithFaker, RefreshDatabase;



    /**
     * It should:
     *  - return 401 when not logged
     *  - return 403 when not ajax
     *  - return 200 and data when authorized
     *
     * @return void
     */
    public function testGetWorkspaces()
    {
        $users = factory(User::class, 2)->create();

        $workspaces = $this->generateWorkspaces($users[0], 1)->merge(
            $this->generateWorkspaces($users[1], 2)
        );

        // NOT LOGGED
        $this->getJson('/workspace', $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->getJson('/workspace')->assertForbidden();

        // VALID REQUEST
        $workspaces[1]->addMember($users[0]);
        $response = $this->getJson('/workspace', $this->ajaxHeader);
        $response->assertOk();
        $response->assertJsonFragment(['id' => $workspaces[0]->id]);
        $response->assertJsonFragment(['id' => $workspaces[1]->id]);
        $response->assertJsonMissing(['id' => $workspaces[2]->id]);


    }

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
    public function testFindWorkspace()
    {
        $users = factory(User::class, 2)->create();

        $workspaces = $this->generateWorkspaces($users[0], 1);

        // NOT LOGGED
        $this->getJson('/workspace'.'/'.$workspaces[0]->id, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->getJson('/workspace'.'/'.$workspaces[0]->id)->assertForbidden();

        // NOT UUID
        $this->getJson('/workspace/notUUID', $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->getJson('/workspace'.'/'.$this->faker()->uuid(), $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->getJson('/workspace'.'/'.$workspaces[0]->id, $this->ajaxHeader)->assertUnauthorized();
        
        // VALID REQUEST
        $workspaces[0]->addMember($users[1]);
        $response = $this->getJson('/workspace'.'/'.$workspaces[0]->id, $this->ajaxHeader);
        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $workspaces[0]->id
        ]);

    }

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
    public function testGetWorkspaceBoards()
    {
        $users = factory(User::class, 2)->create();

        $workspaces = $this->generateWorkspaces($users[0], 1)->merge(
            $this->generateWorkspaces($users[1], 1)
        );

        factory(Board::class)->create([
            'user_id' => $users[0]->id,
            'workspace_id' => $workspaces[0]->id,
        ]);

        factory(Board::class)->create([
            'user_id' => $users[1]->id,
            'workspace_id' => $workspaces[0]->id,
        ]);

        factory(Board::class)->create([
            'user_id' => $users[1]->id,
            'workspace_id' => $workspaces[1]->id,
        ]);

        // NOT LOGGED
        $this->getJson('/workspace'.'/'.$workspaces[0]->id.'/boards', $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($users[0]);
        $this->getJson('/workspace'.'/'.$workspaces[0]->id.'/boards')->assertForbidden();

        // NOT UUID
        $this->getJson('/workspace/notUUID'.'/boards', $this->ajaxHeader)->assertStatus(400);

        // NOT FOUND
        $this->getJson('/workspace'.'/'.$this->faker()->uuid().'/boards', $this->ajaxHeader)->assertNotFound();

        // NOT AUTHORIZED
        $this->actingAs($users[1]);
        $this->getJson('/workspace'.'/'.$workspaces[0]->id.'/boards', $this->ajaxHeader)->assertUnauthorized();
        
        // VALID REQUEST
        $workspaces[0]->addMember($users[1]);
        $response = $this->getJson('/workspace'.'/'.$workspaces[0]->id.'/boards', $this->ajaxHeader);
        $response->assertOk();
        $response->assertJsonFragment(['id' => $workspaces[0]->boards[0]->id]);
        $response->assertJsonFragment(['id' => $workspaces[0]->boards[1]->id]);
        $response->assertJsonMissing(['id' => $workspaces[1]->boards[0]->id]);
    }
}
