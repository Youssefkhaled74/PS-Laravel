<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function register(array $data): array
    {
        $user = User::create([
            'full_name' => $data['full_name'] ?? null,
            'country_code' => $data['country_code'] ?? '+966',
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        // generate OTP for verification
        $otp = $this->otpService->generateOtp($user->country_code, $user->phone, 'REGISTER_VERIFY');

        return ['user' => $user, 'otp' => $otp];
    }
}
