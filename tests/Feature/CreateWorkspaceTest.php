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

    private array $ajaxHeader = ['X-Requested-With' => 'XMLHttpRequest'];

    /**
     * It should insert a new Workspace
     * 
     * @return void
     */
    public function testInsertWorkspace()
    {

        $this->withoutExceptionHandling();

        // Insert new user
        $password = $this->faker->password;
        $userAttributes = [
            'name' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];
        $this->postJSON('/register', $userAttributes);

        // Get the user inserted
        $user = User::where('email', $userAttributes['email'])->firstOrFail();

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
