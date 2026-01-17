<?php

namespace App\Http\Controllers\Api\V1\User\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Cart\UpdateCartItemRequest;
use App\Services\User\Cart\CartService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\Api\V1\Cart\CartResource;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponseTrait;

    protected CartService $service;

    public function __construct(CartService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $cart = $this->service->getOrCreateActiveCart($request->user());
        return $this->success(new CartResource($cart->load('items.vendorItem')), 'checkout.cart.loaded');
    }

    public function update(UpdateCartItemRequest $request, $id)
    {
        $user = $request->user();
        $cart = $this->service->getOrCreateActiveCart($user);
        $item = $cart->items()->where('id', (int)$id)->first();
        if (! $item) return $this->error('checkout.errors.cart_item_not_found', null, 404);
        $item->quantity = $request->validated()['quantity'];
        $item->save();
        return $this->success(new CartResource($this->service->getOrCreateActiveCart($user)->load('items.vendorItem')), 'checkout.cart.updated');
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $cart = $this->service->getOrCreateActiveCart($user);
        $item = $cart->items()->where('id', (int)$id)->first();
        if (! $item) return $this->error('checkout.errors.cart_item_not_found', null, 404);
        $item->delete();
        return $this->success(new CartResource($this->service->getOrCreateActiveCart($user)->load('items.vendorItem')), 'checkout.cart.removed');
    }
}
