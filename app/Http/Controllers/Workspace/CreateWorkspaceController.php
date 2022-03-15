<?php

namespace App\Http\Controllers\Workspace;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Workspace;

class CreateWorkspaceController extends Controller
{
    public function __construct()
    {
        
    }

    public function insert_async(Request $request)
    {
        // Validate
        $validated = Workspace::validate($request);

        // Persist
        Workspace::create($validated);

        // Return response
        return response()->json([
            'code' => 201,
            'status' => 'Created',
            'message' => 'Successfully created Workspace.'
        ], 201);
    }
}