<?php

namespace Tests\Feature;

use App\User;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadWorkspaceTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testReadWorkspace()
    {
        // Generate 2 different users and log into user 2
        $password = $this->faker->sentence();
        $user1 = $this->generateAndInsertNewUser($password, $this->faker->email, $this->faker->userName);
        $user2 = $this->generateAndInsertNewUser($password, $this->faker->email, $this->faker->userName, true);

        // Generate one workspace for user 1 and two for user 2
        $this->generateAndInsertNewWorkspace($user1);
        $this->generateAndInsertNewWorkspace($user2);
        $this->generateAndInsertNewWorkspace($user2);
        
        // Get workspaces created by user
        $user_id = Auth::user()->id;
        $workspaces = Workspace::where(['user_id' => $user_id])->get();

        // ASSERTIONS
        // It should only return workspaces created by user 2
        $count = 0;
        foreach ($workspaces as $workspace) {
            $count++;
            $this->assertEquals($user_id, $workspace->user_id);
        }
    }
}
