<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Label;
use Gate;
use Str;

class ReadLabelController extends Controller
{
    public function show($label_id)
    {
        if ( ! Str::isUuid($label_id) ) {
            return response()->json([], 400);
        }

        $label = Label::findOrFail($label_id);

        if ( Gate::denies('collaborate', $label->board->workspace) ) {
            return response()->json([], 401);
        }

        return response()->json([
            'data' => $label,
        ]);
    }
}
