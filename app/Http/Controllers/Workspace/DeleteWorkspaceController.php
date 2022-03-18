<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Workspace;
use Auth;
use Str;

class DeleteWorkspaceController extends Controller
{
    public function delete($workspace_id)
    {
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json([], 400);
        }

        $workspace = Workspace::find($workspace_id);

        if ( ! $workspace ) {
            return response()->json([], 404);
        }

        if ( ! $workspace->isCreator(Auth::user()) ) {
            return response()->json([], 401);
        }

        $workspace->delete();

        return response()->json([], 204);
    }
}
