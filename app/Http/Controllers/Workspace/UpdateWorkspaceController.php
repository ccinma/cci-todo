<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Workspace;
use Auth;
use Str;

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
}
