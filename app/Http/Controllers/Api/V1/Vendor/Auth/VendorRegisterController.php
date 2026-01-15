<?php

namespace App\Http\Controllers\Api\V1\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Auth\RegisterVendorRequest;
use App\Http\Resources\Api\V1\Vendor\VendorResource;
use App\Services\Vendor\Auth\VendorAuthService;
use App\Traits\ApiResponseTrait;

class VendorRegisterController extends Controller
{
    use ApiResponseTrait;

    protected VendorAuthService $vendorAuthService;

    public function __construct(VendorAuthService $vendorAuthService)
    {
        $this->vendorAuthService = $vendorAuthService;
    }

    /**
     * Register a new vendor
     *
     * @param RegisterVendorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterVendorRequest $request)
    {
        try {
            $result = $this->vendorAuthService->register($request->all());

            return $this->success(
                [
                    'token' => $result['token'],
                    'vendor' => new VendorResource($result['vendor']),
                ],
                'vendor.auth.register_success',
                null,
                201
            );
        } catch (\Exception $e) {
            return $this->error('vendor.auth.register_failed', null, 500);
        }
    }
}
