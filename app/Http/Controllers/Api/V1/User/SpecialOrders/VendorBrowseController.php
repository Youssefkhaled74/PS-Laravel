<?php

namespace App\Http\Controllers\Api\V1\User\SpecialOrders;

use App\Http\Controllers\Controller;
use App\Services\SpecialOrders\UserSpecialOrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class VendorBrowseController extends Controller
{
    use ApiResponseTrait;

    protected UserSpecialOrderService $service;

    public function __construct(UserSpecialOrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = (int)$request->query('per_page', 20);
        $search = $request->query('search');

        $paginator = $this->service->listVendors([
            'per_page' => $perPage,
            'search' => $search,
        ]);

        return $this->success($paginator->items(), 'special_orders.vendor_list', $paginator->toArray());
    }
}
