<?php

namespace App\Http\Controllers\Api\V1\Vendor\Onboarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Onboarding\SelectSubscriptionRequest;
use App\Services\Vendor\Onboarding\VendorOnboardingService;
use App\Services\Vendor\Packages\PackageService;
use App\Http\Resources\Api\V1\Vendor\VendorPackageResource;
use App\Http\Resources\Api\V1\Vendor\VendorPackageAssignmentResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected VendorOnboardingService $onboardingService,
        protected PackageService $packageService
    ) {}

    /**
     * Get all active packages
     * 
     * GET /v1/vendor/packages
     */
    public function packages(): JsonResponse
    {
        try {
            $packages = $this->packageService->getActivePackages();

            return $this->success(
                VendorPackageResource::collection($packages),
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

    /**
     * Select subscription package
     * 
     * POST /v1/vendor/onboarding/subscription/select
     */
    public function select(SelectSubscriptionRequest $request): JsonResponse
    {
        try {
            $vendor = $request->user();
            
            $assignment = $this->onboardingService->selectPackage(
                $vendor,
                $request->validated()
            );

            return $this->success(
                new VendorPackageAssignmentResource($assignment->load('package')),
                'vendor.onboarding.subscription_selected',
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
