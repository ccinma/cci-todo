<?php

namespace Tests\Feature;

use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateWorkspaceTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * It should insert a new Workspace
     * 
     * @return void
     */
    public function testInsertWorkspace()
    {

        $this->withoutExceptionHandling();

        // Insert new user and get it
        $user = $this->generateAndInsertNewUser($this->faker->sentence(), $this->faker->email, $this->faker->userName, true);

        // Insert new workspace by ajax method
        $workspaceAttributes = [
            'user_id' => $user->id,
            'name' => $this->faker->text(50),
        ];
        $this->postJSON('/workspace/insert', $workspaceAttributes, $this->ajaxHeader);

        // ASSERTIONS
        // The workspace should exists in the database
        $this->assertDatabaseHas('workspaces', $workspaceAttributes);
    }
}
