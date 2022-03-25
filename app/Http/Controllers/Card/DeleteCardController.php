<?php

namespace App\Http\Controllers\Card;

use App\Card;
use App\Http\Controllers\Controller;
use Gate;
use Str;

class DeleteCardController extends Controller
{
    public function delete($card_id)
    {
        if ( ! Str::isUuid($card_id) ) {
            return response()->json([], 400);
        }

        $card = Card::findOrFail($card_id);

        if ( Gate::denies('collaborate', $card->lane->board->workspace) ) {
            return response()->json([], 401);
        }

        $previous = null;
        $next = null;

        if ( $card->previous_id ) {
            $previous = Card::find($card->previous_id);
        }

        if ( $card->next_id ) {
            $next = Card::find($card->next_id);
        }

        if ( $previous && $next ) {
            $previous->update(['next_id' => $next->id]);
            $next->update(['previous_id' => $previous->id]);
        } elseif ($previous) {
            $previous->update(['next_id' => null]);
        } elseif ($next) {
            $next->update(['previous' => null]);
        }

        $card->delete();

        return response()->json([], 204);
    }
}
