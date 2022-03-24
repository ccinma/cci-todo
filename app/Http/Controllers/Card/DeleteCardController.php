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

        $card->delete();

        return response()->json([], 204);
    }
}
