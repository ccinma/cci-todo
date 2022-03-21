<?php

namespace App\Http\Controllers\Lane;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLaneRequest;
use App\Lane;
use Auth;
use Str;

class UpdateLaneController extends Controller
{
    public function update($lane_id, UpdateLaneRequest $request)
    {
        if ( ! Str::isUuid($lane_id) ) {
            return response()->json([
                'errors' => [
                    'Lane ID is not a UUID',
                ],
            ], 400);
        }

        $attributes = $request->validated();

        $lane = Lane::find($lane_id);

        if ( ! $lane ) {
            return response()->json([
                'errors' => [
                    'Lane not found',
                ],
            ], 404);
        }

        if ( ! $lane->board->workspace->hasMember(Auth::user()) ) {
            return response()->json([], 401);
        }

        if ( empty($attributes) ) {
            return response()->json([], 304);
        }

        $lane->update($attributes);

        return response()->json([
            'data' => $lane
        ], 200);
    }
}
