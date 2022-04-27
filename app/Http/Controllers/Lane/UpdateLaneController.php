<?php

namespace App\Http\Controllers\Lane;

use App\Http\Controllers\Controller;
use App\Http\Requests\MoveLaneRequest;
use App\Http\Requests\UpdateLaneRequest;
use App\Lane;
use Auth;
use Gate;
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

        $lane = Lane::with(['cards'])->find($lane_id);

        if ( ! $lane ) {
            return response()->json([
                'errors' => [
                    'Lane not found',
                ],
            ], 404);
        }

        if ( Gate::denies('collaborate', $lane->board->workspace) ) {
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



    public function move($lane_id, MoveLaneRequest $request) {
        if ( ! Str::isUuid($lane_id) ) {
            return response()->json([], 400);
        }

        $lane = Lane::findOrFail($lane_id);

        if ( Gate::denies('collaborate', $lane->board->workspace) ) {
            return response()->json([], 401);
        }

        $attributes = $request->validated();

        if ( empty($attributes) || $attributes['previous_id'] == $lane->previous_id ) {
            return response()->json([], 304);
        }

        // Fills the gap left by the moved lane
        if ($lane->previous_id || $lane->next_id) {
            $previous = ($lane->previous_id) ? Lane::find($lane->previous_id) : null;
            $next = ($lane->next_id) ? Lane::find($lane->next_id) : null;
            
            $previous_id = $previous ? $previous->id : null;
            $next_id = $next ? $next->id : null;

            if ($previous) $previous->update(['next_id' => $next_id]);
            if ($next) $next->update(['previous_id' => $previous_id]);
        }

        // Create new link after moved
        
        // Find previous
        
        $previous = null;
        $next = null;

        if (isset($attributes['previous_id'])) {
            $previous = Lane::findOrFail($attributes['previous_id']);
        }

        // Find next if exists
        if ($previous) {
            if ($previous->next_id) {
                $next = Lane::find($previous->next_id);
            }
        } else {
            $next = Lane::where('board_id', '=', $lane->board->id)->where('previous_id', '=', null)->first();
        }

        // Place the moved lane between the previous and the next ones
        if ( $previous && $next ) {
            $previous->update(['next_id' => $lane->id]);
            $next->update(['previous_id' => $lane->id]);
            $lane->update(['previous_id' => $previous->id, 'next_id' => $next->id]);
        } elseif ( $previous ) {
            $previous->update(['next_id' => $lane->id]);
            $lane->update(['previous_id' => $previous->id, 'next_id' => null]);
        } elseif ( $next ) {
            $next->update(['previous_id' => $lane->id]);
            $lane->update(['previous_id' => null, 'next_id' => $next->id]);
        } else {
            $lane->update(['previous_id' => null, 'next_id' => null]);
        }

        return response()->json([
            'data' => [
                'previous' => $previous,
                'moved' => $lane,
                'next' => $next,
            ],
        ]);
    }

}
