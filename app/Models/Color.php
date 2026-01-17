<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasActiveScope;

class Color extends Model
{
    use HasActiveScope;

    protected $fillable = ['name_en','name_ar','hex','status','sort_order'];
}
