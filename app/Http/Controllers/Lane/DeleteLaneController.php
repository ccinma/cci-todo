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

        $previous = null;
        $next = null;

        if ( $lane->previous_id ) {
            $previous = Lane::find($lane->previous_id);
        }

        if ( $lane->next_id ) {
            $next = Lane::find($lane->next_id);
        }

        if ( $previous && $next ) { // Has lanes around
            $previous->update(['next_id' => $next->id]);
            $next->update(['previous_id' => $previous->id]);
        } elseif ( $previous ) { // Is last
            $previous->update(['next_id' => null]);
        } elseif ( $next ) { // Is first
            $next->update(['previous_id' => null]);
        }

        $lane->delete();

        return response()->json([], 204);
    }
}
