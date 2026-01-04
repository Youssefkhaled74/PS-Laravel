<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\Auth\RegisterService;
use App\Traits\ApiResponseTrait;

class RegisterController extends Controller
{
    use ApiResponseTrait;

    protected RegisterService $service;

    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $payload = $request->validated();
        $result = $this->service->register($payload);

        // return user and otp metadata (do not expose hashed values)
        return $this->success(['user' => $result['user'], 'otp' => $result['otp']], 'register_success', null, 201);
    }
}
