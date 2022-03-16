<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Workspace;
use Auth;

class ReadWorkspaceController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        // Get workspaces by user id
        $workspaces = Workspace::findAllByLoggedUser();

        // Return response
        return view('workspace.listing', ['workspaces' => $workspaces]);
    }

    public function show($workspace)
    {
        $workspace = Workspace::find($workspace);

        if (!$workspace || !$workspace->hasMember(Auth::user())) {
            return view('errors.404');
        };

        return view('workspace.show', [
            'workspace' => $workspace
        ]);
    }
}