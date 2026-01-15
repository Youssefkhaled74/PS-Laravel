<?php

namespace App\Http\Controllers\Api\V1\Vendor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Auth\SendVendorOtpRequest;
use App\Http\Requests\Api\V1\Vendor\Auth\VerifyVendorOtpRequest;
use App\Http\Resources\Api\V1\Vendor\VendorResource;
use App\Services\Vendor\Auth\VendorOtpService;
use App\Traits\ApiResponseTrait;

class VendorOtpController extends Controller
{
    use ApiResponseTrait;

    protected VendorOtpService $otpService;

    public function __construct(VendorOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Send OTP to vendor phone
     *
     * @param SendVendorOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(SendVendorOtpRequest $request)
    {
        try {
            $result = $this->otpService->send($request->phone);

            return $this->success(
                $result,
                'vendor.otp.sent',
                null,
                200
            );
        } catch (\Exception $e) {
            return $this->handleOtpException($e);
        }
    }

    /**
     * Verify OTP code
     *
     * @param VerifyVendorOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(VerifyVendorOtpRequest $request)
    {
        try {
            $result = $this->otpService->verify($request->phone, $request->otp);

            return $this->success(
                [
                    'token' => $result['token'],
                    'vendor' => new VendorResource($result['vendor']),
                ],
                'vendor.otp.verified',
                null,
                200
            );
        } catch (\Exception $e) {
            return $this->handleOtpException($e);
        }
    }

    /**
     * Resend OTP code
     *
     * @param SendVendorOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(SendVendorOtpRequest $request)
    {
        try {
            $result = $this->otpService->resend($request->phone);

            return $this->success(
                $result,
                'vendor.otp.resent',
                null,
                200
            );
        } catch (\Exception $e) {
            return $this->handleOtpException($e);
        }
    }

    /**
     * Handle OTP service exceptions
     *
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleOtpException(\Exception $e): \Illuminate\Http\JsonResponse
    {
        $message = $e->getMessage();

        // Parse exception message for special cases
        if (str_starts_with($message, 'otp_locked:')) {
            $seconds = (int) substr($message, strlen('otp_locked:'));
            return $this->error('vendor.otp.locked', ['seconds' => $seconds], 429);
        }

        if (str_starts_with($message, 'wait_before_resend:')) {
            $seconds = (int) substr($message, strlen('wait_before_resend:'));
            return $this->error('vendor.otp.wait_before_resend', ['seconds' => $seconds], 429);
        }

        if (str_starts_with($message, 'otp_invalid:')) {
            $remaining = (int) substr($message, strlen('otp_invalid:'));
            return $this->error('vendor.otp.invalid', ['remaining_attempts' => $remaining], 401);
        }

        if (str_starts_with($message, 'otp_max_attempts:')) {
            $lockSeconds = (int) substr($message, strlen('otp_max_attempts:'));
            return $this->error('vendor.otp.max_attempts', ['lock_seconds' => $lockSeconds], 429);
        }

        // Map other exceptions
        $errorMap = [
            'too_many_requests' => ['vendor.otp.too_many_requests', 429],
            'otp_expired' => ['vendor.otp.expired', 410],
            'otp_not_found' => ['vendor.otp.not_found', 404],
        ];

        if (isset($errorMap[$message])) {
            [$key, $code] = $errorMap[$message];
            return $this->error($key, null, $code);
        }

        // Default error
        return $this->error('vendor.otp.error', null, 500);
    }
}
