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

        $lane = Lane::find($lane_id);

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


        $previous_id = isset($attributes['previous_id']) ? $attributes['previous_id'] : null;
        $next_id = isset($attributes['next_id']) ? $attributes['next_id'] : null;

        $previous = null;
        $next = null;

        if ( $previous_id ) {
            $previous = Lane::findOrFail($previous_id);
            if ( $previous->next_id ) {
                $next = Lane::find($previous->next_id);
            }
        } else {
            $next = Lane::findOrFail($next_id);
            if ( $next->previous_id ) {
                $previous = Lane::find($previous_id);
            }
        }

        $order = [];

        if ( $previous ) {
            $previous->update([
                'next_id' => $lane->id,
            ]);
            $order['previous_id'] = $previous->id;
            $data['previous'] = $previous->withoutRelations();
        }

        if ( $next ) {
            $next->update([
                'previous_id' => $lane->id,
            ]);
            $order['next_id'] = $next->id;
            $data['next'] = $next->withoutRelations();
        }

        $lane->update($order);
        $data['moved'] = $lane->withoutRelations();

        return response()->json([
            'data' => $data
        ], 200);
    }

}
