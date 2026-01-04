<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function login(string $countryCode, string $phone, string $password): array
    {
        $user = User::where('country_code', $countryCode)->where('phone', $phone)->first();
        if (! $user) {
            return ['ok' => false, 'reason' => 'user_not_found'];
        }

        if (! Hash::check($password, $user->password)) {
            return ['ok' => false, 'reason' => 'unauthorized'];
        }

        if (empty($user->phone_verified_at)) {
            return ['ok' => false, 'reason' => 'phone_not_verified', 'user' => $user];
        }

        // Create token (sanctum)
        $token = $user->createToken('api-token')->plainTextToken;

        return ['ok' => true, 'token' => $token, 'user' => $user];
    }
}
