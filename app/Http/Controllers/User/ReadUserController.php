<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Http\Controllers\Controller;

class ReadUserController extends Controller
{
    public function show() {
        $user = Auth::user();
        return response()->json([
            'data' => $user
        ]);
    }
}
