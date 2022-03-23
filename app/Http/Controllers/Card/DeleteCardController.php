<?php

namespace App\Http\Controllers\Card;

use App\Card;
use App\Http\Controllers\Controller;
use Auth;
use Str;

class DeleteCardController extends Controller
{
    public function delete($card_id)
    {
        if ( ! Str::isUuid($card_id) ) {
            return response()->json([], 400);
        }

        $card = Card::findOrFail($card_id);

        if ( ! $card->lane->board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        $card->delete();

        return response()->json([], 204);
    }
}
