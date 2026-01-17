<?php

namespace App\Http\Controllers\Api\V1\User\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Checkout\CheckoutConfirmRequest;
use App\Services\User\Checkout\CheckoutService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\Api\V1\User\OrderResource;

class CheckoutConfirmController extends Controller
{
    use ApiResponseTrait;

    protected CheckoutService $service;

    public function __construct(CheckoutService $service)
    {
        $this->service = $service;
    }

    public function store(CheckoutConfirmRequest $request)
    {
        $user = $request->user();
        try {
            $order = $this->service->confirm($user, $request->validated()['note'] ?? null);
        } catch (\RuntimeException $e) {
            $msg = $e->getMessage();
            return $this->error('checkout.errors.' . $msg);
        }

        return $this->success(new OrderResource($order), 'checkout.order.created', null, 201);
    }
}
