<?php

namespace Tests\Feature;

use App\User;
use App\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateBoardTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testBoardCreation()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $workspace = factory(Workspace::class)->create([
            'user_id' => $user->id
        ]);

        $attributes = [
            'name' => $this->faker()->text(25),
            'workspace_id' => $workspace->id,
        ];

        $this->postJson('/board', $attributes, $this->ajaxHeader)->assertCreated();
        $this->postJson('/board', $attributes)->assertForbidden();
        $this->postJson('/board', [])->assertForbidden();
        $this->postJson('/board')->assertForbidden();

        $attributes['user_id'] = $user->id;

        $this->assertDatabaseHas('boards', $attributes);
    }
}
