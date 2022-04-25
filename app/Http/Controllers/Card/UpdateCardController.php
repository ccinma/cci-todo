<?php

namespace App\Http\Controllers\Card;

use Str;
use Gate;
use App\Card;
use App\Lane;
use App\Http\Controllers\Controller;
use App\Http\Requests\MoveCardRequest;
use App\Http\Requests\UpdateCardRequest;

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
        $lane_id = $attributes['lane_id'];
        $currentLane = Lane::with('board')->findOrFail($lane_id);

        if ($card->lane->board->id != $currentLane->board->id) {
            return response()->json([
                'message' => "Impossible d'envoyer la carte sur un autre tableau."
            ], 403);
        }

        
        // Find previous
        if (isset($attributes['previous_id'])) {
            $previous = Card::findOrFail($attributes['previous_id']);
        }
        

        // Find next if exists
        if ($previous) {
            if ($previous->next_id) {
                $next = Card::find($previous->next_id);
            }
            $lane_id = $previous->lane->id;
        } else {
            $next = Card::where('lane_id', '=', $lane_id)->where('previous_id', '=', null)->first();
            if ($next) {
                $lane_id = $next->lane->id;
            }
        }

        // Place the moved card between the previous and the next ones
        if ( $previous && $next ) {
            $previous->update(['next_id' => $card->id]);
            $next->update(['previous_id' => $card->id]);
            $card->update(['previous_id' => $previous->id, 'next_id' => $next->id, 'lane_id' => $lane_id]);
        } elseif ( $previous ) {
            $previous->update(['next_id' => $card->id]);
            $card->update(['previous_id' => $previous->id, 'next_id' => null, 'lane_id' => $lane_id]);
        } elseif ( $next ) {
            $next->update(['previous_id' => $card->id]);
            $card->update(['previous_id' => null, 'next_id' => $next->id, 'lane_id' => $lane_id]);
        } else {
            $card->update(['previous_id' => null, 'next_id' => null, 'lane_id' => $lane_id]);
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
