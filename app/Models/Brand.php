<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

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
}
