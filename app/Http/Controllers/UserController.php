<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(UserRequest $request)
    {
        $userService = app(UserService::class);
        $user = $userService->register($request->validated());

        return response()->json([
            'user' => $user,
            'message' => 'User registered successfully'
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        try {
            $userService = app(UserService::class);
            $token = $userService->login($request->validated());

            return response()->json([
                'token' => $token,
                'message' => 'Login successful'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
    }
}
