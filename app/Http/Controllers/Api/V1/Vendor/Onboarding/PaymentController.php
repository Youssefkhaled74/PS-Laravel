<?php

namespace App\Http\Controllers\Api\V1\Vendor\Onboarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Onboarding\ConfirmPaymentRequest;
use App\Services\Vendor\Onboarding\VendorOnboardingService;
use App\Services\Vendor\Payment\PaymentService;
use App\Http\Resources\Api\V1\Vendor\PaymentMethodResource;
use App\Http\Resources\Api\V1\Vendor\VendorPaymentAttemptResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected VendorOnboardingService $onboardingService,
        protected PaymentService $paymentService
    ) {}

    /**
     * Get all active payment methods
     * 
     * GET /v1/vendor/payment/methods
     */
    public function methods(): JsonResponse
    {
        try {
            $methods = $this->paymentService->getActivePaymentMethods();

            return $this->success(
                PaymentMethodResource::collection($methods),
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
     * Confirm payment for subscription
     * 
     * POST /v1/vendor/onboarding/payment/confirm
     */
    public function confirm(ConfirmPaymentRequest $request): JsonResponse
    {
        try {
            $vendor = $request->user();
            
            $attempt = $this->onboardingService->confirmPayment(
                $vendor,
                $request->validated()
            );

            // Determine message based on payment status
            $messageKey = match($attempt->status) {
                'paid' => 'vendor.onboarding.payment_confirmed',
                'failed' => 'vendor.onboarding.payment_failed',
                default => 'vendor.onboarding.payment_pending',
            };

            return $this->success(
                new VendorPaymentAttemptResource($attempt->load(['paymentMethod', 'packageAssignment.package'])),
                $messageKey,
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
