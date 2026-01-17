<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Traits\HasActiveScope;

class Brand extends Model
{
    use HasFactory, HasActiveScope;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name_en',
        'name_ar',
        'logo',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function brandLogoUrl(): string
    {
        if (! $this->logo) {
            return asset('images/brand-placeholder.png');
        }
        return asset($this->logo);
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'brand_vendor');
    }
}
