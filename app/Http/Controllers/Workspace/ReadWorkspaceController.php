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

    public function findAllByUserId()
    {
        // Get workspaces by user id
        $user_id = Auth::user()->id;
        $workspaces = Workspace::where(['user_id' => $user_id])->get();

        // Return response
        return view('workspace.listing', ['workspaces' => $workspaces]);
    }
}