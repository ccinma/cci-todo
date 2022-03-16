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
        $attributes = $request->validated();
        $attributes['user_id'] = Auth::user()->id;
        
        $workspace = Workspace::create($attributes);

        return response()->json([
            'data' => $workspace->toArray()
        ], 201);
    }
}