<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasActiveScope;

class Gender extends Model
{
    use HasActiveScope;

    protected $fillable = ['key','name_en','name_ar','status','sort_order'];
}
