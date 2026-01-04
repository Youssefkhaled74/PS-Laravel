<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\PasswordResetService;
use App\Traits\ApiResponseTrait;

class PasswordController extends Controller
{
    use ApiResponseTrait;

    protected PasswordResetService $service;

    public function __construct(PasswordResetService $service)
    {
        $this->service = $service;
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        $p = $request->validated();
        $res = $this->service->sendResetOtp($p['country_code'] ?? '+966', $p['phone']);
        if (! $res['ok']) {
            return $this->error($res['reason'] ?? 'user_not_found', null, 404);
        }

        return $this->success(['otp_id' => $res['otp']['id'], 'expires_at' => $res['otp']['expires_at']], 'password_reset_otp_sent');
    }

    public function reset(ResetPasswordRequest $request)
    {
        $p = $request->validated();
        $res = $this->service->resetPassword($p['country_code'] ?? '+966', $p['phone'], $p['reset_token'], $p['new_password']);
        if (! $res['ok']) {
            return $this->error($res['reason'] ?? 'invalid_reset_token', null, 400);
        }

        return $this->success(null, 'password_reset_success');
    }
}
