<?php

namespace App\Http\Controllers\Api\User\Password;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Services\Auth\PasswordResetService;
use App\Services\Auth\OtpService;
use App\Traits\ApiResponseTrait;

class ForgotPasswordController extends Controller
{
    use ApiResponseTrait;

    protected PasswordResetService $service;
    protected OtpService $otpService;

    public function __construct(PasswordResetService $service, OtpService $otpService)
    {
        $this->service = $service;
        $this->otpService = $otpService;
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        $p = $request->validated();
        $res = $this->service->sendResetOtp($p['country_code'] ?? '+966', $p['phone']);
        if (! $res['ok']) {
            return $this->error($res['reason'] ?? 'user_not_found', null, 404);
        }

        return $this->success(['otp_session_id' => $res['otp']['id']], 'password_reset_otp_sent');
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $p = $request->validated();
        $res = $this->otpService->verifyOtp($p['otp_id'], $p['code'], $p['purpose']);
        if (! $res['ok']) {
            return $this->error('otp_invalid', null, 400);
        }

        $token = $this->otpService->createResetTokenForOtp($p['otp_id']);
        return $this->success(['reset_session_id' => $token], 'otp_verified');
    }
}
