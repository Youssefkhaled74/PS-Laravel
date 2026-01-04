<?php

namespace App\Services\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OtpService
{
    public function generateOtp(string $countryCode, string $phone, string $purpose): array
    {
        $code = random_int(100000, 999999);
        $now = Carbon::now();
        $expires = $now->copy()->addMinutes(5);
        $resend = $now->copy()->addSeconds(30);

        // store hashed code
        $record = DB::table('otps')->insertGetId([
            'country_code' => $countryCode,
            'phone' => $phone,
            'purpose' => $purpose,
            'code_hash' => Hash::make((string)$code),
            'expires_at' => $expires,
            'resend_available_at' => $resend,
            'attempts_count' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // In production send SMS here. For now return the code so tests/UI can use it.
        return ['id' => $record, 'code' => (string)$code, 'expires_at' => $expires->toDateTimeString(), 'resend_available_at' => $resend->toDateTimeString()];
    }

    public function canResend(int $otpId): bool
    {
        $row = DB::table('otps')->where('id', $otpId)->first();
        if (! $row) return false;
        return Carbon::now()->greaterThanOrEqualTo(Carbon::parse($row->resend_available_at));
    }

    public function verifyOtp(int $otpId, string $code, string $purpose): array
    {
        $row = DB::table('otps')->where('id', $otpId)->first();
        if (! $row) {
            return ['ok' => false, 'reason' => 'not_found'];
        }

        if ($row->purpose !== $purpose) {
            return ['ok' => false, 'reason' => 'purpose_mismatch'];
        }

        if (Carbon::now()->greaterThan(Carbon::parse($row->expires_at))) {
            return ['ok' => false, 'reason' => 'expired'];
        }

        if ($row->attempts_count >= 5) {
            return ['ok' => false, 'reason' => 'too_many_attempts'];
        }

        if (! Hash::check($code, $row->code_hash)) {
            DB::table('otps')->where('id', $otpId)->increment('attempts_count');
            return ['ok' => false, 'reason' => 'invalid'];
        }

        DB::table('otps')->where('id', $otpId)->update(['verified_at' => Carbon::now()]);

        return ['ok' => true, 'otp' => $row];
    }

    public function createResetTokenForOtp(int $otpId): string
    {
        $token = Str::random(40);
        DB::table('otps')->where('id', $otpId)->update(['reset_token_hash' => Hash::make($token)]);
        return $token;
    }

    public function consumeResetToken(string $countryCode, string $phone, string $token): bool
    {
        $row = DB::table('otps')
            ->where('country_code', $countryCode)
            ->where('phone', $phone)
            ->whereNotNull('reset_token_hash')
            ->orderByDesc('id')
            ->first();

        if (! $row) return false;

        if (! Hash::check($token, $row->reset_token_hash)) return false;

        // mark verified and remove reset token
        DB::table('otps')->where('id', $row->id)->update(['verified_at' => Carbon::now(), 'reset_token_hash' => null]);
        return true;
    }
}
