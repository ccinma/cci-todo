<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Auth;

class UpdateUserController extends Controller
{
    public function update(UpdateUserRequest $request) {
        $imageName = time().'.'.$request->image->extension();

        $publicPath = public_path('images').DIRECTORY_SEPARATOR;

        $user = Auth::user();
        if ($user->picture) {
            if (!unlink($publicPath.$user->picture)) {}
        }

        $user->update(['image' => $publicPath.$imageName]);
        $request->image->move(public_path('images'), $imageName);
   
        return response()->json([
            'message' => 'Image en ligne !'
        ]);
    }
}
