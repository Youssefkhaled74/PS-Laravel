<?php

namespace App\Http\Controllers\Api\V1\User\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Cart\AddToCartRequest;
use App\Services\User\Cart\CartService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AddToCartController extends Controller
{
    use ApiResponseTrait;

    protected CartService $service;

    public function __construct(CartService $service)
    {
        $this->service = $service;
    }

    public function store(AddToCartRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        try {
            $cart = $this->service->addItem($user, ['item_id' => $data['item_id'], 'quantity' => $data['quantity']]);
        } catch (\InvalidArgumentException $e) {
            $reason = $e->getMessage();
            $key = match ($reason) {
                'item_not_found' => 'cart.errors.item_not_found',
                'invalid_quantity' => 'cart.errors.invalid_quantity',
                'out_of_stock' => 'cart.errors.out_of_stock',
                default => 'error',
            };
            return $this->error($key);
        } catch (\RuntimeException $e) {
            // vendor_mismatch:current:incoming
            if (str_starts_with($e->getMessage(), 'vendor_mismatch')) {
                [$_, $current, $incoming] = explode(':', $e->getMessage());
                return $this->error('cart.errors.vendor_mismatch', ['current_vendor_id' => (int)$current, 'incoming_vendor_id' => (int)$incoming], 409);
            }
            return $this->error('error');
        }

        $payload = $this->service->summarizeCart($cart);
        return $this->success($payload, 'cart.added');
    }
}
