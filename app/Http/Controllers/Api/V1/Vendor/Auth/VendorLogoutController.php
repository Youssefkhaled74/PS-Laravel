<?php

namespace App\Http\Controllers\Api\V1\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Services\Vendor\Auth\VendorAuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class VendorLogoutController extends Controller
{
    use ApiResponseTrait;

    protected VendorAuthService $vendorAuthService;

    public function __construct(VendorAuthService $vendorAuthService)
    {
        $this->vendorAuthService = $vendorAuthService;
    }

    /**
     * Logout vendor (revoke current token)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $vendor = $request->user('vendor');
            $this->vendorAuthService->logout($vendor);

            return $this->success(null, 'vendor.auth.logged_out');
        } catch (\Exception $e) {
            return $this->error('vendor.auth.logout_failed', null, 500);
        }
    }
}
