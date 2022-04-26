<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use Symfony\Component\HttpFoundation\Request;

class UpdateUserController extends Controller
{
    public function update(Request $request) {
        $imageName = time().'.'.$request->image->extension();  
   
        $request->image->move(public_path('images'), $imageName);
   
        return response()->json([
            'message' => 'Image en ligne !'
        ]);
    }
}
