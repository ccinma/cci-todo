<?php

namespace App\Http\Controllers\Board;

use App\Board;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoardRequest;
use Auth;

class CreateBoardController extends Controller
{
    public function store(StoreBoardRequest $request)
    {
        $data = [];
        $code = 201;

        $attributes = $request->validated();
        $attributes['user_id'] = Auth::user()->id;

        $board = Board::create($attributes);

        $data['data'] = $board;

        return response()->json($data, $code);
    }
}
