<?php

namespace App\Services\Vendor\Password;

use App\Models\Vendor;
use App\Models\VendorOtp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VendorPasswordService
{
    public function sendResetOtp(string $phone): array
    {
        $vendor = Vendor::where('phone', $phone)->first();
        if (! $vendor) return ['ok' => false, 'reason' => 'vendor_not_found'];

        $otpService = app(\App\Services\Vendor\Auth\VendorOtpService::class);
        $otp = $otpService->send($phone, 'VENDOR_PASSWORD_RESET');

        return ['ok' => true, 'otp' => $otp, 'vendor' => $vendor];
    }

    public function createResetTokenByOtp(string $phone, string $otpCode): array
    {
        $otpService = app(\App\Services\Vendor\Auth\VendorOtpService::class);
        try {
            $token = $otpService->createResetTokenForPhoneOtp($phone, $otpCode, 'VENDOR_PASSWORD_RESET');
            return ['ok' => true, 'reset_token' => $token];
        } catch (\Exception $e) {
            return ['ok' => false, 'reason' => $e->getMessage()];
        }
    }

    public function resetPassword(string $phone, string $resetToken, string $newPassword): array
    {
        // find latest vendor_otp with reset_token_hash
        $row = DB::table('vendor_otps')
            ->where('phone', $phone)
            ->whereNotNull('reset_token_hash')
            ->orderByDesc('id')
            ->first();

        if (! $row) return ['ok' => false, 'reason' => 'invalid_reset_token'];

        if (! Hash::check($resetToken, $row->reset_token_hash)) {
            return ['ok' => false, 'reason' => 'invalid_reset_token'];
        }

        // consume token
        DB::table('vendor_otps')->where('id', $row->id)->update(['reset_token_hash' => null, 'verified_at' => now()]);

        $vendor = Vendor::where('phone', $phone)->first();
        if (! $vendor) return ['ok' => false, 'reason' => 'vendor_not_found'];

        $vendor->password = Hash::make($newPassword);
        $vendor->save();

        return ['ok' => true, 'vendor' => $vendor];
    }
}
