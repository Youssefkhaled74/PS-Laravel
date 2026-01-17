<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasActiveScope;

class PieceType extends Model
{
    use HasActiveScope;

    protected $fillable = ['name_en','name_ar','status','sort_order'];
}
