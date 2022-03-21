<?php

namespace App\Http\Controllers\Lane;

use App\Http\Controllers\Controller;
use App\Lane;
use Auth;
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

        if ( ! $lane->board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        return response()->json([
            'data' => $lane,
        ]);
    }
}
