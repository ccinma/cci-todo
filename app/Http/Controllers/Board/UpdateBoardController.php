<?php

namespace App\Http\Controllers\Board;

use App\Board;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBoardRequest;
use Auth;

class UpdateBoardController extends Controller
{
    public function update($board, UpdateBoardRequest $request)
    {
        $board = Board::with(['workspace', 'workspace.members'])->find($board);
        $attributes = $request->validated();
        
        if ( ! $board ) {
            return response()->json([], 404);
        }

        if ( ! $board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        if ( empty($attributes) ) {
            return response()->json([], 304);
        }

        $board->update($attributes);

        return response()->json(['data' => $board]);

    }
}
