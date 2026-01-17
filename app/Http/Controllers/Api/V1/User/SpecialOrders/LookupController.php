<?php

namespace App\Http\Controllers\Api\V1\User\SpecialOrders;

use App\Http\Controllers\Controller;
use App\Services\SpecialOrders\UserSpecialOrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LookupController extends Controller
{
    use ApiResponseTrait;

    protected UserSpecialOrderService $service;

    public function __construct(UserSpecialOrderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data = $this->service->getLookups();
        return $this->success($data, 'special_orders.lookups');
    }
}
