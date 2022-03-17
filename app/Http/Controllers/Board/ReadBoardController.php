<?php

namespace App\Http\Controllers\Board;

use App\Board;
use App\Http\Controllers\Controller;
use Auth;

class ReadBoardController extends Controller
{
    public function show($board)
    {
        $board = Board::with('workspace')->find($board);

        if ( ! $board) {
            return view('errors.404');
        }

        if( ! $board->workspace->hasMember(Auth::user())) {
            return view('errors.401');
        }

        return view('board.show', [
            'board' => $board,
        ]);
    }
}
