<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function getProfile(): User
    {
        return Auth::user();
    }

    public function updateProfile(array $data): User
    {
        $user = Auth::user();
        $user->update($data);
        return $user;
    }

    public function changePassword(array $data): bool
    {
        $user = Auth::user();
        if (!Hash::check($data['current_password'], $user->password)) {
            return false;
        }
        $user->password = Hash::make($data['new_password']);
        $user->save();
        return true;
    }
}