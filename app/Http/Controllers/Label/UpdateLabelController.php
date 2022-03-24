<?php

namespace App\Http\Controllers\Label;

use App\Card;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttachLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Label;
use Gate;
use Str;

class UpdateLabelController extends Controller
{
    public function update($label_id, UpdateLabelRequest $request)
    {
        if ( ! Str::isUuid($label_id) ) {
            return response()->json([], 400);
        }

        $label = Label::findOrFail($label_id);

        if ( Gate::denies('collaborate', $label->board->workspace) ) {
            return response()->json([], 401);
        }

        $attributes = $request->validated();
        
        if ( 
            isset($attributes['name']) && $attributes['name'] != $label->name || 
            isset($attributes['color']) && $attributes['color'] != $label->color 
        ) {
            $label->update($attributes);
        } else {
            return response()->json([], 304);
        }

        return response()->json([
            'data' => $label,
        ], 200);
    }



    public function attach($label_id, AttachLabelRequest $request)
    {
        if ( ! Str::isUuid($label_id) ) {
            return response()->json([], 400);
        }

        $label = Label::findOrFail($label_id);

        if ( Gate::denies('collaborate', $label->board->workspace) ) {
            return response()->json([], 401);
        }
        
        $attributes = $request->validated();

        $card = Card::findOrFail($attributes['card_id']);

        if ( $card->lane->board->id != $label->board->id ) {
            return response()->json([
                'errors' => "Can't attach labels and cards on different boards."
            ], 403);
        }

        $card->labels()->attach($label);

        return response([], 204);

    }
}
