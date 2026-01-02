<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Services\ProfileService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

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

    public function getProfile()
    {
        $profileService = app(ProfileService::class);
        $user = $profileService->getProfile();

        return response()->json([
            'user' => $user,
            'message' => 'Profile retrieved successfully'
        ]);
    }


    public function changePassword(UserRequest $request)
    {
        $validatedData = $request->validated();
        
        if (!Hash::check($validatedData['current_password'], auth()->user()->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 401);
        }

        $profileService = app(ProfileService::class);
        $success = $profileService->changePassword([
            'current_password' => $validatedData['current_password'],
            'new_password' => $validatedData['new_password']
        ]);

        if ($success) {
            return response()->json([
                'message' => 'Password changed successfully'
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to change password'
            ], 500);
        }
    }
}
