<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\Auth\OtpService;
use App\Services\Auth\RegisterService;
use App\Services\Auth\PasswordResetService;
use App\Traits\ApiResponseTrait;

class OtpController extends Controller
{
    use ApiResponseTrait;

    protected OtpService $otpService;
    protected RegisterService $registerService;
    protected PasswordResetService $passwordService;

    public function __construct(OtpService $otpService, RegisterService $registerService, PasswordResetService $passwordService)
    {
        $this->otpService = $otpService;
        $this->registerService = $registerService;
        $this->passwordService = $passwordService;
    }

    public function send(SendOtpRequest $request)
    {
        $p = $request->validated();
        $res = $this->otpService->generateOtp($p['country_code'] ?? '+966', $p['phone'], $p['purpose']);
        return $this->success(['otp_id' => $res['id'], 'expires_at' => $res['expires_at'], 'resend_available_at' => $res['resend_available_at']], 'otp_sent');
    }

    public function verify(VerifyOtpRequest $request)
    {
        $p = $request->validated();
        $res = $this->otpService->verifyOtp($p['otp_id'], $p['code'], $p['purpose']);

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

        // on success, if purpose is PASSWORD_RESET return reset token
        if ($p['purpose'] === 'PASSWORD_RESET') {
            $token = $this->otpService->createResetTokenForOtp($p['otp_id']);
            return $this->success(['reset_token' => $token], 'otp_verified');
        }

        // for REGISTER_VERIFY, mark user's phone_verified_at
        if ($p['purpose'] === 'REGISTER_VERIFY') {
            // find user and set phone_verified_at
            $otp = $res['otp'];
            $user = \App\Models\User::where('country_code', $otp->country_code)->where('phone', $otp->phone)->first();
            if ($user) {
                $user->phone_verified_at = now();
                $user->save();
                // create token
                $token = $user->createToken('api-token')->plainTextToken;
                return $this->success(['token' => $token, 'token_type' => 'Bearer', 'user' => $user], 'otp_verified');
            }
        }

        return $this->error('otp_invalid', null, 400);
    }
}
