<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Label;
use Gate;
use Str;

class DeleteLabelController extends Controller
{
    public function delete($label_id)
    {
        if ( ! Str::isUuid($label_id) ) {
            return response()->json([], 400);
        }

        $label = Label::findOrFail($label_id);

        if ( Gate::denies('collaborate', $label->board->workspace) ) {
            return response()->json([], 401);
        }

        $label->delete();

        return response()->json([], 204);
    }
}
