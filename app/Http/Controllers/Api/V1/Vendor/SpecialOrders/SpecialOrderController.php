<?php

namespace App\Http\Controllers\Api\V1\Vendor\SpecialOrders;

use App\Http\Controllers\Controller;
use App\Services\SpecialOrders\VendorSpecialOrderService;
use App\Http\Resources\Api\V1\SpecialOrders\SpecialOrderResource;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\Api\V1\Vendor\SpecialOrders\DecisionRequest;
use Illuminate\Http\Request;

class SpecialOrderController extends Controller
{
    use ApiResponseTrait;

    protected VendorSpecialOrderService $service;

    public function __construct(VendorSpecialOrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $vendor = $request->user();
        $paginator = $this->service->listForVendor($vendor, ['per_page' => (int)$request->query('per_page', 20), 'status' => $request->query('status')]);
        return $this->success(SpecialOrderResource::collection($paginator), 'special_orders.inbox');
    }

    public function show(Request $request, $id)
    {
        $vendor = $request->user();
        $order = $this->service->showForVendor($vendor, (int)$id);
        return $this->success(new SpecialOrderResource($order), 'special_orders.fetched');
    }

    public function decision(DecisionRequest $request, $id)
    {
        $vendor = $request->user();
        $order = $this->service->decide($vendor, (int)$id, $request->validated());
        $messageKey = $request->validated()['decision'] === 'accept' ? 'special_orders.accepted' : 'special_orders.rejected';
        return $this->success(new SpecialOrderResource($order), $messageKey);
    }
}
