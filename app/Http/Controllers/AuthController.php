<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validate = $request->validate([
            'name' => 'required|max:16|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create($validate);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'messages' => 'Create account successfully!',
            'user' => $user,
            'token' => $token
        ],201);
    }
}
