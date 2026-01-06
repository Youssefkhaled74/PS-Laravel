<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Services\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class BrandService
{
    public function __construct(protected UploadService $uploader)
    {
    }

    public function paginate(array $filters = [], int $perPage = 10)
    {
        $q = Brand::query();

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $q->where(function ($q2) use ($s) {
                $q2->where('name_en', 'like', "%{$s}%")
                   ->orWhere('name_ar', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['status']) && in_array($filters['status'], [Brand::STATUS_ACTIVE, Brand::STATUS_INACTIVE])) {
            $q->where('status', $filters['status']);
        }

        $q->orderBy('sort_order', 'asc')->orderBy('id', 'desc');

        return $q->paginate($perPage)->withQueryString();
    }

    public function create(array $data, ?UploadedFile $logo = null): Brand
    {
        $brand = Brand::create(Arr::except($data, ['logo']));
        if ($logo) {
            $path = $this->uploader->uploadPublicImage($logo, 'uploads/brands', 'brand_'.$brand->id);
            $brand->logo = $path;
            $brand->save();
        }
        return $brand;
    }

    public function update(Brand $brand, array $data, ?UploadedFile $logo = null): Brand
    {
        $brand->update(Arr::except($data, ['logo']));
        if ($logo) {
            // delete old
            $this->uploader->deletePublicFile($brand->logo);
            $path = $this->uploader->uploadPublicImage($logo, 'uploads/brands', 'brand_'.$brand->id);
            $brand->logo = $path;
            $brand->save();
        }
        return $brand;
    }

    public function toggleStatus(Brand $brand): Brand
    {
        $brand->status = $brand->status === Brand::STATUS_ACTIVE ? Brand::STATUS_INACTIVE : Brand::STATUS_ACTIVE;
        $brand->save();
        return $brand;
    }

    public function delete(Brand $brand): void
    {
        $this->uploader->deletePublicFile($brand->logo);
        $brand->delete();
    }
}
