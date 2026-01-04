<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'country', 'city', 'district', 'street', 'building_no', 'apartment_no', 'floor', 'postal_code', 'phone', 'notes', 'lat', 'lng', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
