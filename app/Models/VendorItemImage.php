<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorItemImage extends Model
{
    protected $fillable = ['vendor_item_id','path','sort_order'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(VendorItem::class, 'vendor_item_id');
    }
}
