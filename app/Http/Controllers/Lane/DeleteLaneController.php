<?php

namespace App\Http\Controllers\Lane;

use App\Http\Controllers\Controller;
use App\Lane;
use Gate;
use Str;

class DeleteLaneController extends Controller
{
    public function delete($lane_id)
    {
        if ( ! Str::isUuid($lane_id) ) {
            return response()->json([
                'errors' => 'Lane ID is not UUID',
            ], 400);
        }

        $lane = Lane::find($lane_id);

        if ( ! $lane ) {
            return response()->json([], 404);
        }

        if ( Gate::denies('collaborate', $lane->board->workspace) ) {
            return response()->json([], 401);
        }

        $lane->delete();

        return response()->json([], 204);
    }
}
