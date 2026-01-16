<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(VendorPaymentAttempt::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function getName(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        return $this->name[$locale] ?? ($this->name['en'] ?? '');
    }
}
