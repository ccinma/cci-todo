<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Auth;

class UpdateUserController extends Controller
{
    public function update(UpdateUserRequest $request) {
        $imageName = time().'.'.$request->image->extension();

        $publicPath = public_path('images');

        $user = Auth::user();
        if ($user->picture) {
            if (file_exists($publicPath.DIRECTORY_SEPARATOR.$user->picture)) {
                unlink($publicPath.DIRECTORY_SEPARATOR.$user->picture);
            }
        }

        $user->picture = $imageName;
        $user->save();

        $request->image->move(public_path('images'), $imageName);
   
        return response()->json([
            'message' => 'Image en ligne !'
        ]);
    }
}
