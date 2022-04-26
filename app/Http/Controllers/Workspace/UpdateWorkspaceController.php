<?php

namespace App\Http\Controllers\Workspace;

use Str;
use Auth;
use App\User;
use App\Workspace;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AddMemberRequest;
use App\Http\Requests\RemoveMemberRequest;
use App\Http\Requests\UpdateWorkspaceRequest;

class UpdateWorkspaceController extends Controller
{
    public function update($workspace_id, UpdateWorkspaceRequest $request)
    {
        // NOT UUID
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json(['message' => 'Invalid UUID'], 400);
        }

        $attributes = $request->validated();

        $workspace = Workspace::find($workspace_id);

        // NOT FOUND
        if ( ! $workspace ) {
            return response()->json([], 404);
        }

        // NOT AUTHORIZED
        if ( ! $workspace->isCreator(Auth::user()) ) {
            return response()->json([], 401);
        }

        // EMPTY BODY
        if ( empty($attributes) ) {
            return response()->json([], 304);
        }

        $attributes['user_id'] = Auth::user()->id;

        $workspace->update($attributes);

        return response()->json([
            'data' => $workspace
        ]);
    }

    public function addMember($workspace_id, AddMemberRequest $request) {

        // NOT UUID
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json(['message' => 'Invalid UUID'], 400);
        }

        $validated = $request->validated();
        $workspace = Workspace::findOrFail($workspace_id);

        // NOT AUTHORIZED
        if ( Gate::denies('collaborate', $workspace) ) {
            return response()->json([
                'message' => "Vous n'avez pas le droit de faire ça."
            ], 401);
        }

        // FIND USER TO ADD TO WORKSPACE
        $invited = User::where('email', '=', $validated['user_email'])->firstOrFail();
        $workspace->addMember($invited);

        return response()->json([
            'message' => 'Member added successfully !',
            'data' => $invited,
        ], 200);
    }

    public function removeMember($workspace_id, RemoveMemberRequest $request) {

        // NOT UUID
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json(['message' => 'Invalid UUID'], 400);
        }

        $validated = $request->validated();
        $workspace = Workspace::findOrFail($workspace_id);

        // NOT AUTHORIZED
        if ( Gate::denies('collaborate', $workspace) || Gate::denies('manage-workspace', $workspace) && Auth::user()->id != $validated['user_id'] ) {
            return response()->json([
                'message' => "Vous n'avez pas le droit de faire ça."
            ], 401);
        }
        
        $toRemove = User::findOrFail($validated['user_id']);
        $workspace->removeMember($toRemove);

        return response()->json([
            'message' => 'Member removed successfully !'
        ], 200);
    }
}
