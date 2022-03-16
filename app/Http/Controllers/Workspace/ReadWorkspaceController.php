<?php

namespace App\Http\Controllers\Workspace;

use Illuminate\Http\Request;
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
        $workspaces = Workspace::findAllByLoggedUser(Auth::user()->id);

        // Return response
        return view('workspace.listing', ['workspaces' => $workspaces]);
    }

    public function show(int $id)
    {
        $workspace = Workspace::find($id);

        if (!$workspace || !$workspace->hasMember(Auth::user())) {
            return view('errors.404');
        };

        return view('workspace.show', [
            'workspace' => $workspace
        ]);
    }
}