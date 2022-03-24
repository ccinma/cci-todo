<?php

namespace Tests;

use App\Board;
use App\Lane;
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


    protected function generateFollowingLanes(User $creator, Board $board, int $number) {
        $lanes = factory(Lane::class, $number)->create([
            'board_id' => $board->id,
            'user_id' => $creator->id
        ]);

        for ( $i = 0; $i < $number; $i++ ) {

            // Not last
            if ($i < $number - 1) {
                $lanes[$i]->update(['next_id' => $lanes[$i + 1]->id]);
                $lanes[$i + 1]->update(['previous_id' => $lanes[$i]->id]);
            }
            // Not first
            if ($i > 0) {
                $lanes[$i - 1]->update(['next_id' => $lanes[$i]->id]);
                $lanes[$i]->update(['previous_id' => $lanes[$i - 1]->id]);
            }

        }
        
        return $lanes;
    }

}
