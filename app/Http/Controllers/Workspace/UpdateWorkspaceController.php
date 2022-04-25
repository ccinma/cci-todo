<?php

namespace App\Http\Controllers\Workspace;

use Str;
use Auth;
use App\User;
use App\Workspace;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AddMemberRequest;
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
        $workspace = Workspace::find($workspace_id);

        // NOT FOUND
        if ( ! $workspace ) {
            return response()->json([], 404);
        }

        // NOT AUTHORIZED
        if ( Gate::denies('collaborate', $workspace) ) {
            return response()->json([], 401);
        }

        // EMPTY BODY
        if ( empty($validated) ) {
            return response()->json([], 304);
        }

        // FIND USER TO ADD TO WORKSPACE
        $invited = User::where('email', '=', $validated['user_email'])->firstOrFail();
        $workspace->addMember($invited);

        return response()->json([
            'message' => 'Member added successfully !',
        ], 200);
    }
}
