<?php

namespace App\Services\User\Favorites;

use App\Models\User;
use App\Models\UserFavorite;
use App\Models\VendorItem;
use Illuminate\Pagination\LengthAwarePaginator;

class FavoritesService
{
    public function list(User $user, array $filters = []): LengthAwarePaginator
    {
        $q = $filters['q'] ?? null;
        $perPage = (int)($filters['per_page'] ?? 12);

        $query = VendorItem::query()
            ->select('vendor_items.*')
            ->join('user_favorites', 'vendor_items.id', '=', 'user_favorites.product_id')
            ->where('user_favorites.user_id', $user->id)
            ->with(['images','brand','vendor']);

        if ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('vendor_items.name', 'like', "%{$q}%")
                   ->orWhere('vendor_items.promo_title', 'like', "%{$q}%")
                   ->orWhereHas('brand', function ($b) use ($q) {
                        $b->where('name_en', 'like', "%{$q}%")->orWhere('name_ar', 'like', "%{$q}%");
                   })
                   ->orWhereHas('vendor', function ($v) use ($q) {
                        $v->where('name', 'like', "%{$q}%");
                   });
            });
        }

        return $query->orderByDesc('user_favorites.created_at')->paginate($perPage);
    }

    public function toggle(User $user, int $productId): array
    {
        $exists = UserFavorite::where('user_id', $user->id)->where('product_id', $productId)->first();
        if ($exists) {
            $exists->delete();
            return ['is_favorited' => false];
        }

        UserFavorite::create(['user_id' => $user->id, 'product_id' => $productId]);
        return ['is_favorited' => true];
    }

    public function count(User $user): int
    {
        return UserFavorite::where('user_id', $user->id)->count();
    }
}
