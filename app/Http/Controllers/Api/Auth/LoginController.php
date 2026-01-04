<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use App\Services\Auth\LoginService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

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
            return $this->error($res['reason'] ?? 'unauthorized', null, 401);
        }

        return $this->success(['token' => $res['token'], 'token_type' => 'Bearer', 'user' => $res['user']], 'login_success');
    }
}
