<?php

namespace App\Http\Controllers\Api\V1\Vendor\Password;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Password\ForgotVendorPasswordRequest;
use App\Http\Requests\Api\V1\Vendor\Password\VerifyVendorPasswordOtpRequest;
use App\Http\Requests\Api\V1\Vendor\Password\ResetVendorPasswordRequest;
use App\Services\Vendor\Password\VendorPasswordService;
use App\Traits\ApiResponseTrait;

class ForgotPasswordController extends Controller
{
    use ApiResponseTrait;

    protected VendorPasswordService $service;

    public function __construct(VendorPasswordService $service)
    {
        $this->service = $service;
    }

    public function forgot(ForgotVendorPasswordRequest $request)
    {
        $p = $request->validated();
        $res = $this->service->sendResetOtp($p['phone']);
        if (! $res['ok']) {
            return $this->error($res['reason'] ?? 'vendor_not_found', null, 404);
        }

        return $this->success(['otp_session_id' => $res['otp']['otp_id']], 'password_reset_otp_sent');
    }

    public function verifyOtp(VerifyVendorPasswordOtpRequest $request)
    {
        $p = $request->validated();
        $res = $this->service->createResetTokenByOtp($p['phone'], $p['otp']);
        if (! $res['ok']) {
            return $this->error($res['reason'] ?? 'otp_invalid', null, 400);
        }

        return $this->success(['reset_session_id' => $res['reset_token']], 'otp_verified');
    }

    public function reset(ResetVendorPasswordRequest $request)
    {
        $p = $request->validated();
        $res = $this->service->resetPassword($p['phone'], $p['reset_token'], $p['password']);
        if (! $res['ok']) {
            return $this->error($res['reason'] ?? 'invalid_reset_token', null, 400);
        }

        return $this->success(null, 'password_reset_success');
    }
}
