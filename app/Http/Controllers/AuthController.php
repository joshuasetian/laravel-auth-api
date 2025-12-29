<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'message' => 'Create account successfully!',
            'token' => $token,
            'user' => $user
        ],201);
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successfully!',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    public function logout(Request $request) {
        $user = $request->user();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logout successfully!'
        ],200);
    }
}
