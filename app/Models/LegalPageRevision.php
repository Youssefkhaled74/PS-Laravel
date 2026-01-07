<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalPageRevision extends Model
{
    use HasFactory;

    protected $fillable = ['legal_page_id', 'title', 'content', 'status', 'version', 'updated_by_admin_id'];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(LegalPage::class, 'legal_page_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by_admin_id');
    }
}
