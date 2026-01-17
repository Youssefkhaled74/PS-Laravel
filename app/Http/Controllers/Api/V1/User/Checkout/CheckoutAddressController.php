<?php

namespace App\Http\Controllers\Api\V1\User\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Checkout\CheckoutAddressRequest;
use App\Services\User\Checkout\CheckoutService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\AddressResource;

class CheckoutAddressController extends Controller
{
    use ApiResponseTrait;

    protected CheckoutService $service;

    public function __construct(CheckoutService $service)
    {
        $this->service = $service;
    }

    public function store(CheckoutAddressRequest $request)
    {
        $user = $request->user();
        $cart = $this->service->saveAddress($user, $request->validated());
        $address = $cart->address_id ? app(\App\Services\AddressService::class)->findForUser($user, $cart->address_id) : null;
        return $this->success(new AddressResource($address), 'checkout.address.saved');
    }
}
