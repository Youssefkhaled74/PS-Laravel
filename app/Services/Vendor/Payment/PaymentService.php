<?php

namespace App\Services\Vendor\Payment;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;

class PaymentService
{
    /**
     * Get all active payment methods ordered by sort_order
     */
    public function getActivePaymentMethods(): Collection
    {
        return PaymentMethod::active()
            ->ordered()
            ->get();
    }

    /**
     * Get payment method by key
     */
    public function getPaymentMethodByKey(string $key): ?PaymentMethod
    {
        return PaymentMethod::where('key', $key)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Get payment method by ID
     */
    public function getPaymentMethodById(int $id): ?PaymentMethod
    {
        return PaymentMethod::where('id', $id)
            ->where('status', 'active')
            ->first();
    }
}
