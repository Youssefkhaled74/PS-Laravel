<?php

namespace App\Http\Controllers\Api\V1\User\SpecialOrders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\SpecialOrders\CreateSpecialOrderRequest;
use App\Services\SpecialOrders\UserSpecialOrderService;
use App\Http\Resources\Api\V1\SpecialOrders\SpecialOrderResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SpecialOrderController extends Controller
{
    use ApiResponseTrait;

    protected UserSpecialOrderService $service;

    public function __construct(UserSpecialOrderService $service)
    {
        $this->service = $service;
    }

    public function store(CreateSpecialOrderRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image');
        }

        $order = $this->service->create($user, $data);

        return $this->success(new SpecialOrderResource($order), 'special_orders.created', ['next_step' => 'sent'], 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $paginator = $this->service->listForUser($user, ['per_page' => (int)$request->query('per_page',20)]);
        $collection = SpecialOrderResource::collection($paginator);
        return $this->success($collection, 'special_orders.list');
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $order = $this->service->showForUser($user, (int)$id);
        return $this->success(new SpecialOrderResource($order), 'special_orders.fetched');
    }
}
