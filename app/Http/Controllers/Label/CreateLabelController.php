<?php

namespace App\Http\Controllers\Label;

use App\Board;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLabelRequest;
use App\Label;
use Auth;
use Gate;

class CreateLabelController extends Controller
{
    public function store(StoreLabelRequest $request)
    {
        $attributes = $request->validated();

        $board = Board::findOrFail($attributes['board_id']);

        if ( Gate::denies('collaborate', $board->workspace) ) {
            return response()->json([], 401);
        }

        $attributes['user_id'] = Auth::user()->id;

        Label::create($attributes);

        return response()->json([], 201);
    }
}
