<?php

namespace App\Http\Controllers\Api\V1\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Auth\RegisterVendorRequest;
use App\Http\Resources\Api\V1\Vendor\VendorResource;
use App\Services\Vendor\Auth\VendorAuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Log;

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
            // Log full exception for debugging
            Log::error($e);

            // In local environment return the exception message to help debugging
            if (app()->environment('local')) {
                return $this->error($e->getMessage(), null, 500);
            }

            return $this->error('vendor.auth.register_failed', null, 500);
        }
    }
}
