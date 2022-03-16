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
    public function testStoreWorkspace()
    {

        $this->withoutExceptionHandling();

        // Insert new user and get it
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Insert new workspace by ajax method
        $workspaceAttributes = [
            'user_id' => $user->id,
            'name' => $this->faker->text(50),
        ];
        $this->postJSON('/workspace', $workspaceAttributes, $this->ajaxHeader)->assertCreated();
        $this->postJSON('/workspace', $workspaceAttributes)->assertForbidden();
        $this->postJSON('/workspace')->assertForbidden();

        // ASSERTIONS
        // The workspace should exists in the database
        $this->assertDatabaseHas('workspaces', $workspaceAttributes);
    }
}
