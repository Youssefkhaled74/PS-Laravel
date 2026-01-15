<?php

namespace App\Http\Controllers\Api\V1\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Auth\LoginVendorRequest;
use App\Http\Resources\Api\V1\Vendor\VendorResource;
use App\Services\Vendor\Auth\VendorAuthService;
use App\Traits\ApiResponseTrait;

class VendorLoginController extends Controller
{
    use ApiResponseTrait;

    protected VendorAuthService $vendorAuthService;

    public function __construct(VendorAuthService $vendorAuthService)
    {
        $this->vendorAuthService = $vendorAuthService;
    }

    /**
     * Login vendor with phone and password
     *
     * @param LoginVendorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginVendorRequest $request)
    {
        try {
            $result = $this->vendorAuthService->login($request->validated());

            return $this->success(
                [
                    'token' => $result['token'],
                    'vendor' => new VendorResource($result['vendor']),
                ],
                'vendor.auth.login_success'
            );
        } catch (\Exception $e) {
            return $this->error('vendor.auth.invalid_credentials', null, 401);
        }
    }
}
