<?php

namespace App\Http\Controllers\Api\V1\Vendor\Items;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use App\Services\Vendor\Items\VendorItemService;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    use ApiResponseTrait;

    protected VendorItemService $service;

    public function __construct(VendorItemService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data = $this->service->getLookups();
        return $this->success($data, 'vendor.items.lookups');
    }
}
