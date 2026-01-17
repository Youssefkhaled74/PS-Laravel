<?php

namespace App\Services\Vendor\Items;

use App\Models\Vendor;
use App\Models\VendorItem;
use App\Models\VendorItemImage;
use App\Models\Category;
use App\Models\PieceType;
use App\Models\Brand;
use App\Models\Gender;
use App\Models\Size;
use App\Models\Color;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PieceTypeResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\GenderResource;
use App\Http\Resources\SizeResource;
use App\Http\Resources\ColorResource;
use Illuminate\Support\Facades\DB;
use App\Traits\UploadsTrait;

class VendorItemService
{
    use UploadsTrait;

    public function getLookups(): array
    {
        return [
            'categories' => CategoryResource::collection(Category::active()->get()),
            'piece_types' => PieceTypeResource::collection(PieceType::active()->get()),
            'brands' => BrandResource::collection(Brand::active()->get()),
            'genders' => GenderResource::collection(Gender::active()->get()),
            'sizes' => SizeResource::collection(Size::active()->get()),
            'colors' => ColorResource::collection(Color::active()->get()),
        ];
    }

    public function createItem(Vendor $vendor, array $data): VendorItem
    {
        if ($vendor->status !== 'active') {
            abort(403, __('api.vendor.errors.not_approved') ?? 'Not approved');
        }

        DB::beginTransaction();
        try {
            $itemData = collect($data)->only([
                'category_id','piece_type_id','brand_id','gender_id','size_id','color_id','name',
                'quantity_available','quantity_per_client','weight','warranty','promo_title','is_taxable'
            ])->toArray();

            // convert price to cents
            $itemData['price'] = isset($data['price']) ? intval(round($data['price'] * 100)) : 0;
            $itemData['discount_price'] = isset($data['discount_price']) ? intval(round($data['discount_price'] * 100)) : null;
            $itemData['discount_ends_at'] = $data['discount_ends_at'] ?? null;
            $itemData['vendor_id'] = $vendor->id;
            $itemData['status'] = 'pending';

            $item = VendorItem::create($itemData);

            // handle images
            $paths = $this->uploadImages($data['images'] ?? [], 'uploads/vendor/items');
            foreach ($paths as $i => $path) {
                VendorItemImage::create(['vendor_item_id' => $item->id, 'path' => $path, 'sort_order' => $i]);
            }

            DB::commit();
            return $item->load('images');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function listItems(Vendor $vendor, array $filters = [])
    {
        $q = VendorItem::where('vendor_id', $vendor->id);
        if (! empty($filters['status'])) $q->where('status', $filters['status']);
        if (! empty($filters['category_id'])) $q->where('category_id', $filters['category_id']);
        if (! empty($filters['brand_id'])) $q->where('brand_id', $filters['brand_id']);

        return $q->with('images')->paginate(15);
    }

    public function getItem(Vendor $vendor, int $id): VendorItem
    {
        $item = VendorItem::where('id', $id)->where('vendor_id', $vendor->id)->with('images')->firstOrFail();
        return $item;
    }

    public function updateItem(Vendor $vendor, VendorItem $item, array $data): VendorItem
    {
        if ($item->vendor_id !== $vendor->id) abort(403, __('api.vendor.errors.not_owner') ?? 'Not owner');

        DB::beginTransaction();
        try {
            $update = collect($data)->only([
                'category_id','piece_type_id','brand_id','gender_id','size_id','color_id','name',
                'quantity_available','quantity_per_client','weight','warranty','promo_title','is_taxable'
            ])->toArray();

            if (isset($data['price'])) $update['price'] = intval(round($data['price'] * 100));
            if (isset($data['discount_price'])) $update['discount_price'] = intval(round($data['discount_price'] * 100));
            if (isset($data['discount_ends_at'])) $update['discount_ends_at'] = $data['discount_ends_at'];

            $item->update($update);

            // delete images if requested
            if (! empty($data['image_ids_to_delete'])) {
                foreach ($data['image_ids_to_delete'] as $imgId) {
                    $img = VendorItemImage::where('id', $imgId)->where('vendor_item_id', $item->id)->first();
                    if ($img) {
                        $this->deleteFile($img->path);
                        $img->delete();
                    }
                }
            }

            // add new images
            if (! empty($data['images'])) {
                $paths = $this->uploadImages($data['images'], 'uploads/vendor/items');
                foreach ($paths as $i => $path) {
                    VendorItemImage::create(['vendor_item_id' => $item->id, 'path' => $path, 'sort_order' => $i]);
                }
            }

            DB::commit();
            return $item->fresh('images');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteItem(Vendor $vendor, VendorItem $item): void
    {
        if ($item->vendor_id !== $vendor->id) abort(403, __('api.vendor.errors.not_owner') ?? 'Not owner');
        foreach ($item->images as $img) {
            $this->deleteFile($img->path);
        }
        $item->delete();
    }
}
