<?php

namespace App\Http\Controllers\Board;

use App\Board;
use App\Http\Controllers\Controller;
use Auth;
use Str;

class ReadBoardController extends Controller
{
    public function show($board_id)
    {
        if ( ! Str::isUuid($board_id) ) {
            return response()->json([], 400);
        }

        $board = Board::findOrFail($board_id);

        if ( ! $board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        return response([
            'data' => $board->withoutRelations(),
        ]);

    }
}
