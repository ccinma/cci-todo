<?php

namespace Tests\Feature;

use App\User;
use App\Workspace;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Queue\Worker;

class UpdateWorkspaceTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testChangeWorkspaceProperties()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        
        $workspace = factory(Workspace::class)->create([
            'user_id' => $user->id
        ]);

        $attributes = [
            'name' => 'Ceci est un titre modifié.'
        ];

        $this->putJson('/workspace'.'/'.$workspace->id, $attributes, $this->ajaxHeader)->assertOk();
        $this->putJson('/workspace'.'/'.$workspace->id, $attributes)->assertForbidden(); // Not ajax request
        $this->putJson('/workspace/2', $attributes, $this->ajaxHeader)->assertNotFound();

        $workspace = Workspace::find($workspace->id);

        $this->assertEquals($attributes['name'], $workspace->name);
    }
}
