<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VendorStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'title',
        'media_type',
        'media_path',
        'thumb_path',
        'duration_seconds',
        'sort_order',
        'status',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'duration_seconds' => 'integer',
        'sort_order' => 'integer',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(VendorStoryView::class);
    }

    /**
     * Check if story is currently active based on status and schedule
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->start_at && $this->start_at->isFuture()) {
            return false;
        }

        if ($this->end_at && $this->end_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get schedule status badge
     */
    public function getScheduleStatus(): string
    {
        if ($this->status !== 'active') {
            return 'inactive';
        }

        $now = now();

        if ($this->start_at && $this->start_at->isFuture()) {
            return 'upcoming';
        }

        if ($this->end_at && $this->end_at->isPast()) {
            return 'expired';
        }

        return 'active';
    }

    /**
     * Get full media URL
     */
    public function getMediaUrlAttribute(): string
    {
        return asset($this->media_path);
    }

    /**
     * Get full thumb URL
     */
    public function getThumbUrlAttribute(): ?string
    {
        return $this->thumb_path ? asset($this->thumb_path) : null;
    }

    /**
     * Scope: only active stories
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('start_at')
                  ->orWhere('start_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_at')
                  ->orWhere('end_at', '>=', $now);
            });
    }

    /**
     * Scope: order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }
}
