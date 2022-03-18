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
     * It should:
     *  - only accept logged users
     *  - only accept AJAX requests
     *  - create workspace and return it in 201 request
     * 
     * @return void
     */
    public function testStoreWorkspace()
    {
        $user = factory(User::class)->create();

        $attributes = [
            'name' => $this->faker()->text(50),
        ];

        // NOT LOGGED
        $this->postJson('/workspace', $attributes, $this->ajaxHeader)->assertUnauthorized();

        // NOT AJAX
        $this->actingAs($user);
        $this->postJson('/workspace', $attributes)->assertForbidden();

        // NOT ALL REQUIRED FIELDS
        $this->actingAs($user);
        $this->postJson('/workspace', ['test' => 'test'], $this->ajaxHeader)->assertStatus(422);

        // LEGIT REQUEST
        $this->actingAs($user);
        $request = $this->postJson('/workspace', $attributes, $this->ajaxHeader);
        $request->assertCreated();
        $this->assertDatabaseHas('workspaces', $attributes);
        $workspace = Workspace::where($attributes)->firstOrFail();
        $request->assertJsonFragment([
            'data' => $workspace->toArray()
        ]);
    }
}
