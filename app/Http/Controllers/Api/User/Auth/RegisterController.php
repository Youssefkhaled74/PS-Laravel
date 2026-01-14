<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Services\Auth\RegisterService;
use App\Services\Auth\OtpService;
use App\Traits\ApiResponseTrait;

class RegisterController extends Controller
{
    use ApiResponseTrait;

    protected RegisterService $service;
    protected OtpService $otpService;

    public function __construct(RegisterService $service, OtpService $otpService)
    {
        $this->service = $service;
        $this->otpService = $otpService;
    }

    public function register(RegisterRequest $request)
    {
        $payload = $request->validated();
        $res = $this->service->register($payload);

        $user = $res['user'] ?? null;
        $otp = $res['otp'] ?? null;

        if ($user && empty($user->phone_verified_at)) {
            return $this->success(['requires_otp' => true, 'otp_session_id' => $otp['id']], 'register_requires_otp', null, 201);
        }

        // create token if already verified
        if ($user) {
            $token = $user->createToken('api-token')->plainTextToken;
            return $this->success(['token' => $token, 'token_type' => 'Bearer', 'user' => new UserResource($user)], 'register_success', null, 201);
        }

        return $this->error('register_failed', null, 400);
    }
}
