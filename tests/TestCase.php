<?php

namespace Tests;

use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;
    
    /**
     * The headers to emulate an Ajax request.
     */
    protected array $ajaxHeader = ['X-Requested-With' => 'XMLHttpRequest'];


    protected function generateWorkspaces(User $user, ?int $number = null) {

        $workspaces = null;

        if ( $number ) {
            $workspaces = factory(Workspace::class, $number)->create([
                'user_id' => $user->id,
            ]);

            foreach ( $workspaces as $workspace ) {
                $workspace->addMember($user, true);
            }

        } else {
            $workspaces = factory(Workspace::class)->create([
                'user_id' => $user->id,
            ]);
            $workspaces->addMember($user, true);
        }

        return $workspaces;
    }

}
