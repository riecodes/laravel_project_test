<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class Register extends Controller
{
    //
    public function register(RegisterRequest $request) {
        
        // User model handles the hashing in casts()
        $user = User::create($request->validated());

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token'=> $token
        ], 201);
    }
}
