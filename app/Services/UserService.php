<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserService
{
    public function register(array $data): User{
        return User::create([
            ...$data,
            'role' => $data['role'] ?? 'admin', // default role
        ]);
    }

    public function login(array $data): string{
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken('API Token')->plainTextToken;
    }

    public function isAdmin(): bool{
        return Auth::check() && Auth::user()->role === 'admin';
    }
}