<?php

namespace App\Http\Controllers\Card;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCardRequest;
use App\Lane;
use Auth;

class CreateCardController extends Controller
{
    public function store(StoreCardRequest $request)
    {
        $attributes = $request->validated();

        $lane = Lane::find($attributes['lane_id']);
        
        if ( ! $lane ) {
            return response()->json([], 404);
        }

        if ( ! $lane->board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        $attributes['user_id'] = Auth::user()->id;

        $card = Card::create($attributes);

        return response()->json([
            'data' => $card
        ], 201);
    }
}
