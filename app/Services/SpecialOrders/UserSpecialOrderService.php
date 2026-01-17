<?php

namespace App\Services\SpecialOrders;

use App\Models\SpecialOrder;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\PieceType;
use App\Models\Brand;
use App\Models\Gender;
use App\Models\Size;
use App\Models\Color;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Traits\UploadsTrait;
use Illuminate\Support\Facades\DB;

class UserSpecialOrderService
{
    use UploadsTrait;

    public function getLookups(): array
    {
        $categories = Category::where('status', 'active')->get();
        $pieceTypes = PieceType::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        $genders = Gender::where('status', 'active')->get();
        $sizes = Size::where('status', 'active')->get();
        $colors = Color::where('status', 'active')->get();

        return compact('categories','pieceTypes','brands','genders','sizes','colors');
    }

    public function listVendors(array $filters = []): LengthAwarePaginator
    {
        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 20;
        $perPage = max(1, min(50, $perPage));

        $q = Vendor::query()->where('status', 'active');

        if (!empty($filters['search'])) {
            $q->where('name', 'like', '%' . $filters['search'] . '%');
        }

        // If there is a vendor->category relation in future this can filter
        return $q->paginate($perPage);
    }

    public function create(User $user, array $data): SpecialOrder
    {
        return DB::transaction(function () use ($user, $data) {
            $orderData = [
                'user_id' => $user->id,
                'vendor_id' => $data['vendor_id'],
                'category_id' => $data['category_id'],
                'piece_type_id' => $data['piece_type_id'],
                'brand_id' => $data['brand_id'] ?? null,
                'gender_id' => $data['gender_id'] ?? null,
                'size_id' => $data['size_id'] ?? null,
                'color_id' => $data['color_id'] ?? null,
                'location_text' => $data['location_text'] ?? null,
                'lat' => $data['lat'] ?? null,
                'lng' => $data['lng'] ?? null,
                'details' => $data['details'] ?? null,
                'urgent' => !empty($data['urgent']),
                'status' => 'pending',
            ];

            if (!empty($data['image'])) {
                $path = $this->uploadImage($data['image'], 'special_orders/images');
                $orderData['image_path'] = $path;
            }

            $order = SpecialOrder::create($orderData);

            // TODO: send notification to vendor using vendor notification service if exists

            return $order->refresh();
        });
    }

    public function listForUser(User $user, array $filters = []): LengthAwarePaginator
    {
        $perPage = isset($filters['per_page']) ? (int)$filters['per_page'] : 20;
        $perPage = max(1, min(50, $perPage));

        $q = SpecialOrder::where('user_id', $user->id)->orderBy('created_at', 'desc');
        return $q->paginate($perPage);
    }

    public function showForUser(User $user, int $id): SpecialOrder
    {
        $order = SpecialOrder::where('user_id', $user->id)->where('id', $id)->firstOrFail();
        return $order;
    }
}
