<?php

namespace App\Http\Controllers\Card;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Requests\MoveCardRequest;
use App\Http\Requests\UpdateCardRequest;
use Gate;
use Str;

class UpdateCardController extends Controller
{
    public function update($card_id, UpdateCardRequest $request)
    {
        if ( ! Str::isUuid($card_id) ) {
            return response()->json([], 400);
        }

        $card = Card::findOrFail($card_id);

        if ( Gate::denies('collaborate', $card->lane->board->workspace) ) {
            return response()->json([], 401);
        }

        $attributes = $request->validated();

        if ( empty($attributes) ) {
            return response()->json([], 304);
        }

        $card->update($attributes);

        return response()->json([
            'data' => $card,
        ], 200);

    }


    public function move($card_id, MoveCardRequest $request)
    {
        if ( ! Str::isUuid($card_id) ) {
            return response()->json([], 400);
        }

        $card = Card::findOrFail($card_id);

        if ( Gate::denies('collaborate', $card->lane->board->workspace) ) {
            return response()->json([], 401);
        }

        $attributes = $request->validated();

        if ( empty($attributes) ) {
            return response()->json([], 304);
        }

        $previous = null;
        $next = null;

        if ( isset($attributes['previous_id']) ) {
            $previous = Card::findOrFail($attributes['previous_id']);
            if ( $previous->next_id ) {
                $next = Card::find($previous->next_id);
            }
        } elseif ( isset($attributes['next_id']) ) {
            $next = Card::findOrFail($attributes['next_id']);
            if ( $next->previous_id ) {
                $previous = Card::find($next->previous_id);
            }
        }

        if ( $previous && $next ) {
            $previous->update(['next_id' => $card->id]);
            $next->update(['previous_id' => $card->id]);
            $card->update(['previous_id' => $previous->id, 'next_id' => $next->id]);
        } elseif ( $previous ) {
            $previous->update(['next_id' => $card->id]);
            $card->update(['previous_id' => $previous->id]);
        } elseif ( $next ) {
            $next->update(['previous_id' => $card->id]);
            $card->update(['next_id' => $next->id]);
        }

        return response()->json([
            'data' => [
                'previous' => $previous,
                'moved' => $card,
                'next' => $next,
            ],
        ]);

    }

}
