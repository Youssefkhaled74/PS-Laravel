<?php

namespace App\Http\Controllers\Api\V1\Vendor\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Vendor\Items\StoreVendorItemRequest;
use App\Http\Requests\Api\V1\Vendor\Items\UpdateVendorItemRequest;
use App\Http\Resources\Api\V1\Vendor\Items\VendorItemResource;
use App\Models\VendorItem;
use App\Services\Vendor\Items\VendorItemService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    use ApiResponseTrait;

    protected VendorItemService $service;

    public function __construct(VendorItemService $service)
    {
        $this->service = $service;
    }

    public function lookups(Request $request)
    {
        return $this->success($this->service->getLookups(), 'vendor.items.lookups');
    }

    public function index(Request $request)
    {
        $vendor = $request->user();
        $items = $this->service->listItems($vendor, $request->only(['status','category_id','brand_id']));
        return $this->paginated(VendorItemResource::collection($items));
    }

    public function store(StoreVendorItemRequest $request)
    {
        $vendor = $request->user();
        try {
            $item = $this->service->createItem($vendor, $request->all());
            return $this->success(new VendorItemResource($item), 'vendor.items.created', null, 201);
        } catch (\Exception $e) {
            return $this->error('error', null, 500);
        }
    }

    public function show(Request $request, $id)
    {
        $vendor = $request->user();
        $item = $this->service->getItem($vendor, (int)$id);
        return $this->success(new VendorItemResource($item));
    }

    public function update(UpdateVendorItemRequest $request, $id)
    {
        $vendor = $request->user();
        $item = VendorItem::findOrFail($id);
        $item = $this->service->updateItem($vendor, $item, $request->all());
        return $this->success(new VendorItemResource($item), 'vendor.items.updated');
    }

    public function destroy(Request $request, $id)
    {
        $vendor = $request->user();
        $item = VendorItem::findOrFail($id);
        $this->service->deleteItem($vendor, $item);
        return $this->success(null, 'vendor.items.deleted');
    }
}
