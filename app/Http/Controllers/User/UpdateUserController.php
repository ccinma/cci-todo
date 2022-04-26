<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Requests\UpdateUserInfosRequest;
use Auth;

class UpdateUserController extends Controller
{
    public function updateImage(UpdateUserImageRequest $request) {
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

    public function updateInfos(UpdateUserInfosRequest $request) {
        $user = Auth::user();
        $validated = $request->validated();

        if (empty($validated)) {
            return response()->json(['message' => 'Champs non valides'], 422);
        }

        if ($validated['name'] === $user->name) {
            return response()->json([], 304);
        }

        $user->update($validated);
        return response()->json(['data' => $user], 200);
    }
}
