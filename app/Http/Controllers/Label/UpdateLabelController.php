<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLabelRequest;
use App\Label;
use Auth;
use PhpParser\Node\Stmt\Break_;
use Str;

class UpdateLabelController extends Controller
{
    public function update($label_id, UpdateLabelRequest $request)
    {
        if ( ! Str::isUuid($label_id) ) {
            return response()->json([], 400);
        }

        $label = Label::findOrFail($label_id);

        if ( ! $label->board->workspace->hasMember(Auth::user()) ) {
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
}
