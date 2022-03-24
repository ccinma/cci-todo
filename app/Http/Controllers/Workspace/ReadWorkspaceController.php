<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Workspace;
use Auth;
use Gate;
use Str;

class ReadWorkspaceController extends Controller
{

    public function index()
    {
        $workspaces = Workspace::findUserWorkspaces();

        return response()->json([
            'data' => $workspaces
        ], 200);
    }

    public function show($workspace_id)
    {
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json([], 400);
        }

        $workspace = Workspace::findOrFail($workspace_id);

        if ( Gate::denies('collaborate', $workspace) ) {
            return response()->json([], 401);
        };

        return response()->json([
            'data' => $workspace->withoutRelations(),
        ]);
    }

    public function boards($workspace_id)
    {
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json([], 400);
        }

        $workspace = Workspace::findOrFail($workspace_id);

        if ( Gate::denies('collaborate', $workspace) ) {
            return response()->json([], 401);
        }

        return response()->json([
            'data' => $workspace->boards
        ]);
    }
}