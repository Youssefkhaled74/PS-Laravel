<?php

namespace App\Services\Admin\Items;

use App\Models\VendorItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ItemService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        $q = VendorItem::query()->with(['vendor','images']);

        if (! empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        if (! empty($filters['q'])) {
            $term = $filters['q'];
            $q->where(function ($s) use ($term) {
                $s->where('name', 'like', "%{$term}%")
                  ->orWhereHas('vendor', function ($v) use ($term) {
                      $v->where('name', 'like', "%{$term}%");
                  });
            });
        }

        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 20;
        $perPage = max(1, min(100, $perPage));

        return $q->orderBy('created_at','desc')->paginate($perPage);
    }

    public function findOrFail(int $id): VendorItem
    {
        return VendorItem::with(['vendor','images'])->findOrFail($id);
    }
}
