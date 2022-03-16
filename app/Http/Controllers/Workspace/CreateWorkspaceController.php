<?php

namespace App\Http\Controllers\Workspace;

use App\Workspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkspaceRequest;
use Auth;

class CreateWorkspaceController extends Controller
{
    public function __construct()
    {
        
    }

    public function store(StoreWorkspaceRequest $request)
    {
        $validated = $request->validated();

        if (Auth::user()->id != $validated['user_id']) {
            return response()->json([], 401);
        }
        
        $workspace = Workspace::create($validated);

        return response()->json([
            'data' => $workspace->toArray()
        ], 201);
    }
}