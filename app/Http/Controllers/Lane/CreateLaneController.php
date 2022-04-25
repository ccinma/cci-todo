<?php

namespace App\Http\Controllers\Lane;

use App\Board;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaneRequest;
use App\Lane;
use Auth;
use Gate;

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

        if ( Gate::denies('collaborate', $board->workspace) ) {
            return response()->json([], 401);
        }

        $attributes['user_id'] = Auth::user()->id;

        $lastLane = $board->lanes()->where('next_id', null)->first();

        if ( $lastLane ) {
            $attributes['previous_id'] = $lastLane->id;
        }

        $lane = Lane::create($attributes);
        $lane->load('cards');

        if ( $lastLane ) {
            $lastLane->update(['next_id' => $lane->id]);
        }

        return response()->json([
            'data' => $lane,
        ], 201);
    }
}
