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


    public function move($lane_id, MoveLaneRequest $request)
    {
        $previous = null;
        $next = null;

        if ( ! Str::isUuid($lane_id) ) {
            return response()->json([], 400);
        }

        $lane = Lane::findOrFail($lane_id);

        if ( Gate::denies('collaborate', $lane->board->workspace) ) {
            return response()->json([], 401);
        }

        $attributes = $request->validated();

        if ( empty($attributes) ) {
            return response()->json([], 304);
        }

        $data = [];

        // Fills the gap left by the moved lane by connecting the lanes
        $previousIdBeforeMove = $lane->previous_id;
        $nextIdBeforeMove = $lane->next_id;
        $previousBeforeMove = null;
        $nextBeforeMove = null;
        if ($previousIdBeforeMove) {
            $previousBeforeMove = Lane::findOrFail($previousIdBeforeMove);
            $previousBeforeMove->update(['next_id' => $nextIdBeforeMove]);
        }
        if ($nextIdBeforeMove) {
            $nextBeforeMove = Lane::findOrFail($nextIdBeforeMove);
            $nextBeforeMove->update(['previous_id' => $previousIdBeforeMove]);
        }

        // Update the new next and previous lanes
        $previous_id = isset($attributes['previous_id']) ? $attributes['previous_id'] : null;

        $previous = null;
        $next = null;

        // Find the previous and next lanes
        if ( $previous_id ) {
            $previous = Lane::findOrFail($previous_id);
            if ( $previous->next_id ) {
                $next = Lane::find($previous->next_id);
            }
        } else {
            $next = Lane::where('previous_id', '=', null)->firstOrFail();
        }

        $order = [];

        if ( $previous ) {
            $previous->update([
                'next_id' => $lane->id,
            ]);
            $order['previous_id'] = $previous->id;
            $data['previous'] = $previous->withoutRelations();
        } else {
            $order['previous_id'] = null;
        }

        if ( $next ) {
            $next->update([
                'previous_id' => $lane->id,
            ]);
            $order['next_id'] = $next->id;
            $data['next'] = $next->withoutRelations();
        } else {
            $order['next_id'] = null;
        }

        $lane->update($order);
        $data['moved'] = $lane->withoutRelations();

        return response()->json([
            'data' => $data
        ], 200);
    }

}
