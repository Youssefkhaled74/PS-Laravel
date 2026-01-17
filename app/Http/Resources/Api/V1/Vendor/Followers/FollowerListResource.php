<?php

namespace App\Http\Resources\Api\V1\Vendor\Followers;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FollowerListResource extends ResourceCollection
{
    public $collects = \App\Http\Resources\Api\V1\User\UserMiniResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
