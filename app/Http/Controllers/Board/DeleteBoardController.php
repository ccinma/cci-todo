<?php

namespace App\Http\Controllers\Board;

use App\Board;
use App\Http\Controllers\Controller;
use Gate;

class DeleteBoardController extends Controller
{
    public function delete($board)
    {
        $board = Board::find($board);

        if ( ! $board ) {
            return response()->json([], 404);
        }

        if ( Gate::denies('collaborate', $board->workspace) ) {
            return response()->json([], 401);
        }

        Board::destroy($board->id);

        return response()->json([], 204);
    }
}
