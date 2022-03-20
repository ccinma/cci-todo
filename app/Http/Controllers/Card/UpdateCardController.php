<?php

namespace App\Http\Controllers\Card;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCardRequest;
use Auth;
use Str;

class UpdateCardController extends Controller
{
    public function update($card_id, UpdateCardRequest $request)
    {
        if ( ! Str::isUuid($card_id) ) {
            return response()->json([], 400);
        }

        $card = Card::findOrFail($card_id);

        if ( ! $card->lane->board->workspace->hasMember(Auth::user()) ) {
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
}
