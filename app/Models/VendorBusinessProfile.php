<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorBusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id','commercial_name','activity_id','id_number','commercial_register_number','freelance_doc_number','bank_id','bank_account_number','status',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
