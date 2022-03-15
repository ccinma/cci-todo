<?php

namespace App\Http\Controllers\Workspace;

use App\Workspace;
use Illuminate\Http\Request;
use App\Services\WorkspaceServices;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkspaceRequest;

class CreateWorkspaceController extends Controller
{
    public function __construct()
    {
        
    }

    public function store(StoreWorkspaceRequest $request)
    {
        $validated = $request->validated();

        $workspace = Workspace::create($validated);

        return response()->json([
            'data' => $workspace->toArray()
        ], 201);
    }
}