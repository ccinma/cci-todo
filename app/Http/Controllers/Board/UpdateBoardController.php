<?php

namespace App\Http\Controllers\Board;

use App\Board;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBoardRequest;
use Auth;
use Str;

class UpdateBoardController extends Controller
{
    public function update($board_id, UpdateBoardRequest $request)
    {
        if ( ! Str::isUuid($board_id) ) {
            return response()->json([], 400);
        }

        $board = Board::findOrFail($board_id);

        if ( ! $board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        $attributes = $request->validated();

        if ( empty($attributes) ) {
            return response()->json([], 304);
        }

        $board->update($attributes);

        return response()->json([
            'data' => $board->withoutRelations(),
        ], 200);

    }
}
