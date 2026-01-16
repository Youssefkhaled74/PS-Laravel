<?php

namespace App\Http\Controllers\Api\V1\Vendor\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Vendor\VendorResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get authenticated vendor profile
     * 
     * GET /v1/vendor/me
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $vendor = $request->user()->load([
                'businessProfile.bank',
                'brands',
                'documents',
                'activePackageAssignment.package'
            ]);

            return $this->success(
                new VendorResource($vendor),
                'success',
                null,
                200
            );

        } catch (\Exception $e) {
            return $this->error(
                'error',
                ['message' => $e->getMessage()],
                500
            );
        }
    }
}
