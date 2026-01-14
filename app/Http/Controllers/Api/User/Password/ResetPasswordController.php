<?php

namespace App\Http\Controllers\Api\User\Password;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Auth\PasswordResetService;
use App\Traits\ApiResponseTrait;

class ResetPasswordController extends Controller
{
    use ApiResponseTrait;

    protected PasswordResetService $service;

    public function __construct(PasswordResetService $service)
    {
        $this->service = $service;
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
