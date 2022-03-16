<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Workspace;
use Auth;

class DeleteWorkspaceController extends Controller
{
    public function delete($workspace)
    {
        $code = 500;
        $data = [];

        $workspace = Workspace::find($workspace);

        if ( ! $workspace) {
            $code = 404;
        } elseif ( ! $workspace->isCreator(Auth::user())) {
            $code = 401;
        } else {
            $workspace->delete();
            $code = 200;
        }

        return response()->json($data, $code);
    }
}
