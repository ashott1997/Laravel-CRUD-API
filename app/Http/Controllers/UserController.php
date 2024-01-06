<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registration(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $userFields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user= User::create($userFields);
        $tokenLifeCycle = Carbon::now()->addMinutes(60);
        $token = $user->createToken('myapp-token', ['*'], $tokenLifeCycle)->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'User created successfully'
        ];

        return response($response, 201);
    }

    public function login(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $userFields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $userFields['email'])->first();

        if(!$user || !Hash::check($userFields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $tokenLifeCycle = Carbon::now()->addMinutes(60);
        $token = $user->createToken('myapp-token', ['*'], $tokenLifeCycle)->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'User logged in successfully'
        ];

        return response($response, 201);
    }
}
