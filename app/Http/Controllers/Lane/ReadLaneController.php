<?php

namespace App\Http\Controllers\Lane;

use App\Http\Controllers\Controller;
use App\Lane;
use Auth;
use Gate;
use Str;

class ReadLaneController extends Controller
{
    public function show($lane_id)
    {
        if ( ! Str::isUuid($lane_id) ) {
            return response()->json([
                'errors' => [
                    'Lane ID is not UUID'
                ]
            ], 400);
        }

        $lane = Lane::find($lane_id);

        if ( ! $lane ) {
            return response()->json([], 404);
        }

        if ( Gate::denies('collaborate', $lane->board->workspace) ) {
            return response()->json([], 401);
        }

        return response()->json([
            'data' => $lane,
        ]);
    }
}
