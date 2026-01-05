<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'status',
        'sort_order',
    ];

    /**
     * Is category active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
