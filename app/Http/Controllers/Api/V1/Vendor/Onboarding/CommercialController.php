<?php

namespace App\Http\Controllers\Api\V1\Vendor\Onboarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Onboarding\CommercialRequest;
use App\Services\Vendor\Onboarding\VendorOnboardingService;
use App\Http\Resources\Api\V1\Vendor\VendorResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CommercialController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected VendorOnboardingService $onboardingService
    ) {}

    /**
     * Save vendor commercial data
     * 
     * POST /v1/vendor/onboarding/commercial
     */
    public function store(CommercialRequest $request): JsonResponse
    {
        try {
            $vendor = $request->user();
            
            $updatedVendor = $this->onboardingService->saveCommercialData(
                $vendor,
                $request->validated()
            );

            return $this->success(
                new VendorResource($updatedVendor),
                'vendor.onboarding.commercial_saved',
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
