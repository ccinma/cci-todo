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

    public function listAllUserWorkspaces()
    {
        // Get workspaces by user id
        $workspaces = Workspace::findAllByLoggedUser(Auth::user()->id);

        // Return response
        return view('workspace.listing', ['workspaces' => $workspaces]);
    }
}