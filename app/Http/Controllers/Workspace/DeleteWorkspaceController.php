<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Workspace;
use Auth;
use Gate;
use Str;

class DeleteWorkspaceController extends Controller
{
    public function delete($workspace_id)
    {
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json([], 400);
        }

        $workspace = Workspace::findOrFail($workspace_id);

        if ( ! Gate::allows('delete-workspace', $workspace) ) {
            return response()->json([], 401);
        }

        $workspace->delete();

        return response()->json([], 204);
    }
}
