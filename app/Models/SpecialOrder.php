<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialOrder extends Model
{
    use HasFactory;

    protected $table = 'special_orders';

    protected $fillable = [
        'user_id','vendor_id','category_id','piece_type_id','brand_id','gender_id','size_id','color_id',
        'location_text','lat','lng','details','urgent','image_path','status','rejection_reason',
    ];

    protected $casts = [
        'urgent' => 'boolean',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pieceType()
    {
        return $this->belongsTo(PieceType::class, 'piece_type_id');
    }
}
