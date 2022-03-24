<?php

namespace App\Http\Controllers\Board;

use App\Board;
use App\Workspace;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoardRequest;
use Auth;
use Gate;

class CreateBoardController extends Controller
{
    public function store(StoreBoardRequest $request)
    {
        $attributes = $request->validated();
        
        $workspace = Workspace::find($attributes['workspace_id']);
        
        if ( ! $workspace ) {
            return response()->json([], 404);
        }
        
        if ( Gate::denies('collaborate', $workspace) ) {
            return response()->json([], 401);
        }
        
        $attributes['user_id'] = Auth::user()->id;
        
        $board = Board::create($attributes);

        return response()->json([
            'data' => $board,
        ], 201);
    }
}
