<?php

namespace App\Http\Resources\Api\V1\User\Follow;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FollowingListResource extends ResourceCollection
{
    public $collects = \App\Http\Resources\Api\V1\Vendor\VendorMiniResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
