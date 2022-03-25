<?php

namespace Tests;

use App\Board;
use App\Card;
use App\Lane;
use App\User;
use App\Workspace;
use BadFunctionCallException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, WithFaker;
    
    /**
     * The headers to emulate an Ajax request.
     */
    protected array $ajaxHeader = ['X-Requested-With' => 'XMLHttpRequest'];


    protected function generateWorkspaces(User $user, int $number = 1) {

        $workspaces = null;

        if ( $number < 1 ) {
            throw new BadFunctionCallException('Number must be superior as 0.');
        }

        $workspaces = factory(Workspace::class, $number)->create([
            'user_id' => $user->id,
        ]);

        foreach ( $workspaces as $workspace ) {
            $workspace->addMember($user, true);
        }

        return $workspaces;
    }


    private function generateFollowingModels(string $model, User $creator, $parent, int $number) {

        $models = null;

        switch ($model) {
            case Lane::class:
                $models = factory(Lane::class, $number)->create([
                    'board_id' => $parent->id,
                    'user_id' => $creator->id
                ]);
                break;
            case Card::class:
                $models = factory(Card::class, $number)->create([
                    'lane_id' => $parent->id,
                    'user_id' => $creator->id,
                ]);
                break;
            default:
                abort(500);
        }

        for ( $i = 0; $i < $number; $i++ ) {

            // Not last
            if ($i < $number - 1) {
                $models[$i]->update(['next_id' => $models[$i + 1]->id]);
                $models[$i + 1]->update(['previous_id' => $models[$i]->id]);
            }
            // Not first
            if ($i > 0) {
                $models[$i - 1]->update(['next_id' => $models[$i]->id]);
                $models[$i]->update(['previous_id' => $models[$i - 1]->id]);
            }

        }
        
        return $models;
    }

    protected function generateLanes(User $creator, Board $board, int $number) {
        return $this->generateFollowingModels(Lane::class, $creator, $board, $number);
    }

    protected function generateCards(User $creator, Lane $lane, int $number) {
        return $this->generateFollowingModels(Card::class, $creator, $lane, $number);
    }

}
