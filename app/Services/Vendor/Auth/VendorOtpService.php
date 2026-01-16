<?php

namespace App\Services\Vendor\Auth;

use App\Models\Vendor;
use App\Models\VendorOtp;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class VendorOtpService
{
    protected SmsService $smsService;

    // Configuration constants
    const OTP_LENGTH = 6;
    const OTP_EXPIRY_MINUTES = 5;
    const RESEND_COOLDOWN_SECONDS = 30;
    const MAX_VERIFY_ATTEMPTS = 5;
    const LOCK_DURATION_MINUTES = 10;
    const MAX_SENDS_PER_HOUR = 5;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send OTP to vendor phone
     *
     * @param string $phone
     * @return array
     * @throws \Exception
     */
    public function send(string $phone): array
    {
        $vendor = Vendor::where('phone', $phone)->firstOrFail();

        // Check if vendor is locked
        if ($this->isVendorLocked($vendor)) {
            $lockExpiresIn = Carbon::parse($vendor->otp_locked_until)->diffInSeconds(now());
            throw new \Exception('otp_locked:' . $lockExpiresIn);
        }

        // Check resend cooldown
        $latestOtp = $this->getLatestOtp($vendor);
        if ($latestOtp && !$latestOtp->canResend()) {
            throw new \Exception('wait_before_resend:' . $latestOtp->getResendInSeconds());
        }

        // Check rate limit (max sends per hour)
        $recentSendsCount = VendorOtp::where('vendor_id', $vendor->id)
            ->where('created_at', '>', now()->subHour())
            ->count();

        if ($recentSendsCount >= self::MAX_SENDS_PER_HOUR) {
            throw new \Exception('too_many_requests');
        }

        // Generate OTP
        $otp = $this->generateOtp();
        $otpHash = Hash::make($otp);

        DB::beginTransaction();
        try {
            // Create OTP record
            $vendorOtp = VendorOtp::create([
                'vendor_id' => $vendor->id,
                'phone' => $phone,
                'otp_hash' => $otpHash,
                'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
                'resend_available_at' => now()->addSeconds(self::RESEND_COOLDOWN_SECONDS),
                'attempts' => 0,
            ]);

            // Store plain OTP in cache for debugging (short-lived) when debug enabled
            if (config('app.otp_debug', false)) {
                Cache::put('otp_plain_' . $vendorOtp->id, $otp, now()->addMinutes(self::OTP_EXPIRY_MINUTES));
            }

            // Update vendor
            $vendor->update([
                'otp_last_sent_at' => now(),
            ]);

            // Send SMS
            $this->smsService->sendOtp($phone, $otp);

            DB::commit();

            $response = [
                'phone' => $phone,
                'resend_in_seconds' => self::RESEND_COOLDOWN_SECONDS,
                'expires_in_seconds' => self::OTP_EXPIRY_MINUTES * 60,
            ];

            // Include OTP in response for development (if enabled)
            if (config('app.otp_debug', false)) {
                $response['otp'] = $otp;
            }

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Verify OTP code
     *
     * @param string $phone
     * @param string $otp
     * @return array
     * @throws \Exception
     */
    public function verify(string $phone, string $otp): array
    {
        $vendor = Vendor::where('phone', $phone)->firstOrFail();

        // Check if vendor is locked
        if ($this->isVendorLocked($vendor)) {
            $lockExpiresIn = Carbon::parse($vendor->otp_locked_until)->diffInSeconds(now());
            throw new \Exception('otp_locked:' . $lockExpiresIn);
        }

        // Get latest unconsumed OTP
        $vendorOtp = VendorOtp::where('vendor_id', $vendor->id)
            ->whereNull('consumed_at')
            ->latest('created_at')
            ->first();

        if (!$vendorOtp) {
            throw new \Exception('otp_not_found');
        }

        // Check if OTP is expired
        if ($vendorOtp->isExpired()) {
            throw new \Exception('otp_expired');
        }

        // Verify OTP
        if (!Hash::check($otp, $vendorOtp->otp_hash)) {
            return $this->handleInvalidOtp($vendor, $vendorOtp);
        }

        // OTP is valid
        DB::beginTransaction();
        try {
            // Mark OTP as consumed
            $vendorOtp->update([
                'consumed_at' => now(),
            ]);

            // Update vendor
            $vendor->update([
                'phone_verified_at' => now(),
                'otp_attempts' => 0,
                'otp_locked_until' => null,
            ]);

            // Generate Sanctum token
            $token = $vendor->createToken('vendor-token')->plainTextToken;

            DB::commit();

            return [
                'token' => $token,
                'vendor' => $vendor->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Resend OTP (calls send internally)
     *
     * @param string $phone
     * @return array
     */
    public function resend(string $phone): array
    {
        return $this->send($phone);
    }

    /**
     * Generate random OTP code
     *
     * @return string
     */
    protected function generateOtp(): string
    {
        // If otp_debug is enabled return a fixed OTP for development/testing
        if (config('app.otp_debug', false)) {
            return str_pad('111111', self::OTP_LENGTH, '0', STR_PAD_LEFT);
        }

        return str_pad((string) random_int(0, 999999), self::OTP_LENGTH, '0', STR_PAD_LEFT);
    }

    /**
     * Check if vendor is locked
     *
     * @param Vendor $vendor
     * @return bool
     */
    protected function isVendorLocked(Vendor $vendor): bool
    {
        return $vendor->otp_locked_until && Carbon::parse($vendor->otp_locked_until)->isFuture();
    }

    /**
     * Get latest OTP for vendor
     *
     * @param Vendor $vendor
     * @return VendorOtp|null
     */
    protected function getLatestOtp(Vendor $vendor): ?VendorOtp
    {
        return VendorOtp::where('vendor_id', $vendor->id)
            ->whereNull('consumed_at')
            ->latest('created_at')
            ->first();
    }

    /**
     * Handle invalid OTP attempt
     *
     * @param Vendor $vendor
     * @param VendorOtp $vendorOtp
     * @return array
     * @throws \Exception
     */
    protected function handleInvalidOtp(Vendor $vendor, VendorOtp $vendorOtp): array
    {
        DB::beginTransaction();
        try {
            // Increment attempts
            $vendorOtp->increment('attempts');
            $vendor->increment('otp_attempts');

            // Check if max attempts reached
            if ($vendor->fresh()->otp_attempts >= self::MAX_VERIFY_ATTEMPTS) {
                $vendor->update([
                    'otp_locked_until' => now()->addMinutes(self::LOCK_DURATION_MINUTES),
                ]);

                DB::commit();
                throw new \Exception('otp_max_attempts:' . (self::LOCK_DURATION_MINUTES * 60));
            }

            DB::commit();
            
            $remainingAttempts = self::MAX_VERIFY_ATTEMPTS - $vendor->fresh()->otp_attempts;
            throw new \Exception('otp_invalid:' . $remainingAttempts);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
