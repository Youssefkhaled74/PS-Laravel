<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorNotification extends Model
{
    use HasFactory;

    protected $table = 'vendor_notifications';

    protected $fillable = [
        'vendor_id', 'type', 'icon', 'title', 'body', 'data', 'read_at',
    ];

    protected $casts = [
        'title' => 'array',
        'body' => 'array',
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
