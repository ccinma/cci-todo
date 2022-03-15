<?php

namespace Tests;

use App\User;
use App\Workspace;
use Auth;
use Hash;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;
    
    /**
     * The headers to emulate an Ajax request.
     */
    protected array $ajaxHeader = ['X-Requested-With' => 'XMLHttpRequest'];

    /**
     * Generate a new user, then insert it in the database. Login the user is possible but optional.
     * 
     * @param bool $doLogin If you wish to immediately log the freshly created user, set this param to true. Be aware that the current logged user will be logged out.
     * 
     * @return User
     */
    protected function generateAndInsertNewUser(bool $doLogin = false) : User
    {
        // Insert new user
        $password = $this->faker()->sentence();
        $attributes = [
            'email' => $this->faker()->email,
            'name' => $this->faker()->userName,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $user = null;
        if ($doLogin) {
            if (Auth::user()) {
                $this->get('/logout');
            }
            $this->postJSON('/register', $attributes);
            $user = User::where('email', $attributes['email'])->firstOrFail();
        } else {
            $attributes['password'] = Hash::make($password);
            $user = User::create($attributes);
        }

        return $user;
    }

    /**
     * Generate a new Workspace, then insert it in the database.
     * 
     * @param User $user The user that is creating the Workspace. 
     * 
     * @return Workspace
     */
    protected function generateAndInsertNewWorkspace(User $user) : Workspace
    {
        // Insert new workspace by ajax method
        $workspaceAttributes = [
            'user_id' => $user->id,
            'name' => $this->faker->text(50),
        ];
        $workspace = Workspace::create($workspaceAttributes);
        return $workspace;
    }


    /**
     * Determine if a user is a member of a workspace.
     * 
     * @param Workspace $workspace
     * @param User $user
     * 
     * @return bool
     */
    function isWorkspaceMember(Workspace $workspace, User $user) : bool
    {
        $value = false;
        if ($workspace->user_id == $user->id) {
            $value = true;
        } else {
            foreach($workspace->members as $member) {
                if ($member->id == $user->id) {
                    $value = true;
                    break;
                }
            }
        }
        return $value;
    }
}
