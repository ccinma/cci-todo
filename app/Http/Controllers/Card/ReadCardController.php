<?php

namespace App\Http\Controllers\Card;

use App\Card;
use App\Http\Controllers\Controller;
use Auth;
use Str;

class ReadCardController extends Controller
{
    public function show($card_id)
    {
        if ( ! Str::isUuid($card_id) ) {
            return response()->json([], 400);
        }

        $card = Card::find($card_id);

        if ( ! $card ) {
            return response()->json([], 404);
        }

        if ( ! $card->lane->board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        return response()->json([
            'data' => $card
        ], 200);
    }
}
