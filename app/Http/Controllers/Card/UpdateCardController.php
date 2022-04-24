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

        // Fills the gap left by the moved card
        if ($card->previous_id || $card->next_id) {
            $previous = ($card->previous_id) ? Card::find($card->previous_id) : null;
            $next = ($card->next_id) ? Card::find($card->next_id) : null;
            
            $previous_id = $previous ? $previous->id : null;
            $next_id = $next ? $next->id : null;

            if ($previous) $previous->update(['next_id' => $next_id]);
            if ($next) $next->update(['previous_id' => $previous_id]);
        }

        // Create new link after moved
        $previous = null;
        $next = null;
        
        // Find previous
        if (isset($attributes['previous_id'])) {
            $previous = Card::findOrFail($attributes['previous_id']);
        }

        // Find next if exists
        if ($previous) {
            if ($previous->next_id) {
                $next = Card::find($previous->next_id);
            }
        } else {
            $next = Card::where('lane_id', '=', $card->lane->id)->where('previous_id', '=', null)->first();
        }
        
        if ( $previous && $next ) {
            $previous->update(['next_id' => $card->id]);
            $next->update(['previous_id' => $card->id]);
            $card->update(['previous_id' => $previous->id, 'next_id' => $next->id]);
        } elseif ( $previous ) {
            $previous->update(['next_id' => $card->id]);
            $card->update(['previous_id' => $previous->id, 'next_id' => null]);
        } elseif ( $next ) {
            $next->update(['previous_id' => $card->id]);
            $card->update(['previous_id' => null, 'next_id' => $next->id]);
        } else {
            $card->update(['previous_id' => null, 'next_id' => null]);
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
