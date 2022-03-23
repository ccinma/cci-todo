<?php

namespace App\Http\Controllers\Workspace;

use App\Workspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkspaceRequest;
use Auth;

class CreateWorkspaceController extends Controller
{

    public function store(StoreWorkspaceRequest $request)
    {
        $attributes = $request->validated();
        $attributes['user_id'] = Auth::user()->id;

        $workspace = Workspace::create($attributes);
        $workspace->addMember(Auth::user());

        return response()->json([
            'data' => $workspace
        ], 201);
    }
}