<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $driver;

    public function __construct()
    {
        $this->driver = config('services.sms.driver', 'log');
    }

    /**
     * Send SMS message
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send(string $phone, string $message): bool
    {
        switch ($this->driver) {
            case 'log':
                return $this->sendViaLog($phone, $message);
            
            // Add more drivers here (twilio, nexmo, etc.)
            default:
                return $this->sendViaLog($phone, $message);
        }
    }

    /**
     * Send OTP via log (for development)
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    protected function sendViaLog(string $phone, string $message): bool
    {
        Log::info('=== SMS SENT ===', [
            'to' => $phone,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
        ]);

        return true;
    }

    /**
     * Send OTP code
     *
     * @param string $phone
     * @param string $otp
     * @return bool
     */
    public function sendOtp(string $phone, string $otp): bool
    {
        $message = "Your PS verification code is: {$otp}. Valid for 5 minutes.";
        
        // Add Arabic message if needed
        $messageAr = "رمز التحقق الخاص بك في PS هو: {$otp}. صالح لمدة 5 دقائق.";
        
        return $this->send($phone, "{$message}\n\n{$messageAr}");
    }
}
