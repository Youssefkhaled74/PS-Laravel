<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\LoginService;
use App\Traits\ApiResponseTrait;

class LoginController extends Controller
{
    use ApiResponseTrait;

    protected LoginService $service;

    public function __construct(LoginService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $res = $this->service->login($data['country_code'] ?? '+966', $data['phone'], $data['password']);

        if (! $res['ok']) {
            if ($res['reason'] === 'phone_not_verified') {
                return $this->success(['requires_otp' => true, 'otp_session_id' => isset($res['user']) ? optional($res['user'])->id : null], 'login_requires_otp', null, 200);
            }
            return $this->error($res['reason'] ?? 'unauthorized', null, 401);
        }

        return $this->success(['token' => $res['token'], 'token_type' => 'Bearer', 'user' => new UserResource($res['user'])], 'login_success');
    }
}
