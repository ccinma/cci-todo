<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Workspace;
use Auth;

class UpdateWorkspaceController extends Controller
{
    public function update(int $workspace, UpdateWorkspaceRequest $request)
    {
        $workspace = Workspace::find($workspace);

        $code = 500;
        $data = [];

        if (! $workspace) {
            $code = 404;
        } else if ($workspace->user_id != Auth::user()->id) { // If the user is not the creator
            $code = 401;
        } else {
            $workspace = Workspace::where('id', $workspace->id)->update($request->toArray());
            $code = 200;
            $data['data'] = $workspace;
        }

        return response()->json($data, $code);
    }
}
