<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'key', 'title', 'content', 'status', 'version', 'updated_by_admin_id'
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
    ];

    public function revisions()
    {
        return $this->hasMany(LegalPageRevision::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by_admin_id');
    }
}
