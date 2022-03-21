<?php

namespace App\Http\Controllers\Lane;

use App\Board;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaneRequest;
use App\Lane;
use Auth;

class CreateLaneController extends Controller
{
    public function store(StoreLaneRequest $request) 
    {
        $attributes = $request->validated();

        $board = Board::find($attributes['board_id']);

        if ( ! $board ) {
            return response()->json([
                'errors' => [
                    'Board not found.'
                ],
            ], 404);
        }

        if ( ! $board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        $attributes['user_id'] = Auth::user()->id;

        $lane = Lane::create($attributes);

        return response()->json([
            'data' => $lane,
        ], 201);
    }
}
