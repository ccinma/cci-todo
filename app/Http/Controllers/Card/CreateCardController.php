<?php

namespace App\Http\Controllers\Card;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCardRequest;
use App\Lane;
use Auth;
use Gate;

class CreateCardController extends Controller
{
    public function store(StoreCardRequest $request)
    {
        $attributes = $request->validated();

        $lane = Lane::find($attributes['lane_id']);
        
        if ( ! $lane ) {
            return response()->json([], 404);
        }

        if ( Gate::denies('collaborate', $lane->board->workspace) ) {
            return response()->json([], 401);
        }

        $previous = $lane->cards()->where('next_id', null)->first();
        
        $attributes['user_id'] = Auth::user()->id;

        if ( $previous ) {
            $attributes['previous_id'] = $previous->id;
        }
        
        $card = Card::create($attributes);
        
        if ( $previous ) {
            $previous->update(['next_id' => $card->id]);
        }
        
        return response()->json([
            'data' => $card
        ], 201);
    }
}
