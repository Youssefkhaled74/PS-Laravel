<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPaymentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'vendor_package_assignment_id',
        'payment_method_id',
        'billing_period',
        'amount',
        'vat',
        'total',
        'status',
        'reference',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function packageAssignment(): BelongsTo
    {
        return $this->belongsTo(VendorPackageAssignment::class, 'vendor_package_assignment_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function getAmountInSAR(): float
    {
        return $this->amount / 100;
    }

    public function getVatInSAR(): float
    {
        return $this->vat / 100;
    }

    public function getTotalInSAR(): float
    {
        return $this->total / 100;
    }
}
