<?php

namespace App\Http\Controllers\Api\V1\Vendor\Shipping;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Shipping\ShippingDetailsRequest;
use App\Http\Resources\Api\V1\Vendor\Shipping\ShippingDetailsResource;
use App\Services\Vendor\Shipping\ShippingDetailsService;
use App\Models\Vendor;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ShippingDetailsController extends Controller
{
    use ApiResponseTrait;

    protected ShippingDetailsService $service;

    public function __construct(ShippingDetailsService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        /** @var Vendor $vendor */
        $vendor = $request->user();

        $detail = $this->service->getForVendor($vendor);

        if (! $detail) {
            return $this->success(null, 'shipping.not_set');
        }

        return $this->success(new ShippingDetailsResource($detail), 'shipping.fetched');
    }

    public function store(ShippingDetailsRequest $request)
    {
        /** @var Vendor $vendor */
        $vendor = $request->user();

        $detail = $this->service->upsertForVendor($vendor, $request->validated());

        return $this->success(new ShippingDetailsResource($detail), 'shipping.saved');
    }
}
