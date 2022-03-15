<?php

namespace Tests\Feature;

use App;
use App\User;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadWorkspaceTest extends TestCase
{
    use WithFaker, RefreshDatabase;



    /**
     * It should return only the workspaces that are created by or which are trusting the logged user.
     *
     * @return void
     */
    public function testFindWorkspacesByLoggedUser()
    {
        // Generate 2 different users and log the first user
        $users = factory(User::class, 2)->create();
        $this->actingAs($users[0]);

        // Generate 3 workspace for the first user and 2 other for second user
        //  Workspace 1 = created by user 1
        //  Workspace 2 = created by user 1
        //  Workspace 3 = created by user 1
        //  Workspace 4 = created by user 2
        //  Workspace 5 = created by user 2
        $workspaces = factory(Workspace::class, 3)->create([
            'user_id' => $users[0]->id,
        ])->merge(
            factory(Workspace::class, 2)->create([
                'user_id' => $users[1]->id,
            ])
        );

        // Setting additional members for workspaces
        //  Workspace 1 = members: user1
        //  Workspace 2 = members: user1
        //  Workspace 3 = members: user1
        //  Workspace 4 = members: user1, user2
        //  Workspace 5 = members: user2
        $workspaces[3]->addTrustedUser($users[0]);
        
        // Get workspaces created by the logged user
        $userWorkspaces = Workspace::findAllByLoggedUser();

        // ASSERTIONS
        // It should only return workspaces where user1 is a member
        foreach ($userWorkspaces as $workspace) {
            $this->assertEquals(true, $workspace->hasMember($users[0]));
        }
        
        // It should see this created workspace (workspace 1)
        $this->get('/workspace')->assertSee($workspaces[0]->name);
        // It should see this authorized workspace (workspace 4)
        $this->get('/workspace')->assertSee($workspaces[3]->name);
        // It should not see this not authorized workspace (workspace 5)
        $this->get('/workspace')->assertDontSee($workspaces[4]->name);
    }

    /**
     * It should return a specific workspace only if the user is the creator or a member of the workspace.
     * 
     * @return void
     */
    // public function testFindOneWorkspaceByLoggedUserId()
    // {
    //     // Generate 2 different users and log the first user
    //     $user = $this->generateAndInsertNewUser(true);
    //     $workspace = Workspace::findOneByLoggedUser();
    //     $this->assertEquals(true, $workspace->hasMember($user));
    // }
}
