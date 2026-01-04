<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Auth\OtpService;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function sendResetOtp(string $countryCode, string $phone): array
    {
        $user = User::where('country_code', $countryCode)->where('phone', $phone)->first();
        if (! $user) {
            return ['ok' => false, 'reason' => 'user_not_found'];
        }

        $otp = $this->otpService->generateOtp($countryCode, $phone, 'PASSWORD_RESET');
        return ['ok' => true, 'otp' => $otp, 'user' => $user];
    }

    public function resetPassword(string $countryCode, string $phone, string $resetToken, string $newPassword): array
    {
        $ok = $this->otpService->consumeResetToken($countryCode, $phone, $resetToken);
        if (! $ok) {
            return ['ok' => false, 'reason' => 'invalid_reset_token'];
        }

        $user = User::where('country_code', $countryCode)->where('phone', $phone)->first();
        if (! $user) return ['ok' => false, 'reason' => 'user_not_found'];

        $user->password = Hash::make($newPassword);
        $user->save();

        return ['ok' => true, 'user' => $user];
    }
}
