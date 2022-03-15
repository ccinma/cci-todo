<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    
    protected array $ajaxHeader = ['X-Requested-With' => 'XMLHttpRequest'];

    protected function generateAndInsertNewUser(string $password, string $email, string $name, bool $doLogin = false) {
        // Insert new user
        $userAttributes = [
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        if ($doLogin) {
            $this->postJSON('/register', $userAttributes);
        } else {
            User::create($userAttributes);
        }

        // Get the user inserted
        $user = User::where('email', $email)->firstOrFail();

        return $user;
    }

    protected function generateAndInsertNewWorkspace(User $user) {
        // Insert new workspace by ajax method
        $workspaceAttributes = [
            'user_id' => $user->id,
            'name' => $this->faker->text(50),
        ];
        $this->postJSON('/workspace/insert', $workspaceAttributes, $this->ajaxHeader);
    }
}
