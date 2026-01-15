<?php

namespace App\Http\Controllers\Api\User\OTP;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\Auth\OtpService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    use ApiResponseTrait;

    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function send(SendOtpRequest $request)
    {
        $p = $request->validated();
        $res = $this->otpService->generateOtp($p['country_code'] ?? '+966', $p['phone'], $p['purpose'] ?? 'REGISTER_VERIFY');
        return $this->success(['otp_session_id' => $res['id'], 'expires_at' => $res['expires_at'], 'resend_available_at' => $res['resend_available_at']], 'otp_sent');
    }

    public function verify(VerifyOtpRequest $request)
    {
        $p = $request->validated();

        // Support verification by otp_id OR by phone (latest otp record)
        $otpId = $p['otp_id'] ?? null;
        if (empty($otpId) && ! empty($p['phone'])) {
            $country = $p['country_code'] ?? '+966';
            $row = \Illuminate\Support\Facades\DB::table('otps')
                ->where('country_code', $country)
                ->where('phone', $p['phone'])
                ->where('purpose', $p['purpose'])
                ->orderByDesc('id')
                ->first();

            if (! $row) {
                return $this->error('otp_invalid', null, 400);
            }

            $otpId = $row->id;
        }

        if (empty($otpId)) {
            return $this->error('otp_invalid', null, 400);
        }

        $res = $this->otpService->verifyOtp((int)$otpId, $p['code'], $p['purpose']);

        if (! $res['ok']) {
            $map = [
                'not_found' => 'otp_invalid',
                'invalid' => 'otp_invalid',
                'expired' => 'otp_expired',
                'too_many_attempts' => 'otp_too_many_attempts',
                'purpose_mismatch' => 'otp_invalid',
            ];

            $key = $map[$res['reason']] ?? 'otp_invalid';
            return $this->error($key, null, 400);
        }

        if ($p['purpose'] === 'PASSWORD_RESET') {
            $token = $this->otpService->createResetTokenForOtp($p['otp_id']);
            return $this->success(['reset_token' => $token], 'otp_verified');
        }

        if ($p['purpose'] === 'REGISTER_VERIFY') {
            $otp = $res['otp'];
            $user = \App\Models\User::where('country_code', $otp->country_code)->where('phone', $otp->phone)->first();
            if ($user) {
                $user->phone_verified_at = now();
                $user->save();
                $token = $user->createToken('api-token')->plainTextToken;
                return $this->success(['token' => $token, 'token_type' => 'Bearer', 'user' => $user], 'otp_verified');
            }
        }

        return $this->error('otp_invalid', null, 400);
    }

    public function resend(Request $request)
    {
        $p = $request->validate(['otp_id' => ['required','integer']]);
        $ok = $this->otpService->canResend((int)$p['otp_id']);
        if (! $ok) return $this->error('otp_resend_not_allowed', null, 400);

        // TODO: generate new OTP or allow resend; for now return success
        return $this->success(null, 'otp_resent');
    }
}
