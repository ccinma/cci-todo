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
        $user_id = Auth::user()->id;
        $workspaces = Workspace::with(['boards', 'members'])->whereHas('members', function($q) use($user_id) {
            $q->whereIn('user_id', [$user_id]);
        })->get();

        return response()->json([
            'data' => $workspaces
        ], 200);
    }

    public function show($workspace_id)
    {
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json([], 400);
        }

        $workspace = Workspace::with(['members', 'boards', 'boards.labels', 'boards.lanes', 'boards.lanes.cards'])->findOrFail($workspace_id);

        if ( Gate::denies('collaborate', $workspace) ) {
            return response()->json([], 401);
        };

        return response()->json([
            'data' => $workspace,
        ]);
    }

    public function boards($workspace_id)
    {
        if ( ! Str::isUuid($workspace_id) ) {
            return response()->json([], 400);
        }

        $workspace = Workspace::with('boards')->findOrFail($workspace_id);

        if ( Gate::denies('collaborate', $workspace) ) {
            return response()->json([], 401);
        }

        return response()->json([
            'data' => $workspace
        ]);
    }
}