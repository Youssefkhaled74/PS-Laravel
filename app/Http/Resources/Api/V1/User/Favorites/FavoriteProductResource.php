<?php

namespace App\Http\Resources\Api\V1\User\Favorites;

use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteProductResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = app()->getLocale();

        $brandName = null;
        if ($this->brand) {
            $brandName = $this->brand->name_en ?? ($this->brand->name_ar ?? null);
            if ($locale === 'ar' && ! empty($this->brand->name_ar)) $brandName = $this->brand->name_ar;
        }

        $name = $this->name;
        if (is_array($this->name)) {
            $name = $this->name[$locale] ?? $this->name['en'] ?? reset($this->name);
        }

        $mainImage = optional($this->images->first())->path ? asset(optional($this->images->first())->path) : null;

        return [
            'id' => $this->id,
            'name' => $name,
            'brand' => $brandName,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'image_url' => $mainImage,
            'vendor' => $this->vendor ? ['id' => $this->vendor->id, 'name' => $this->vendor->name] : null,
            'is_favorited' => true,
            'favorited_at' => optional($this->pivot?->created_at) ?? null,
        ];
    }
}
