<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'name', 'monthly_price', 'yearly_price', 'currency', 'sort_order', 'status',
    ];

    protected $casts = [
        'name' => 'array',
        'monthly_price' => 'integer',
        'yearly_price' => 'integer',
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public function getName(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        return $this->name[$locale] ?? ($this->name['en'] ?? '');
    }
}
