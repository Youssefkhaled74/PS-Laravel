<?php

namespace App\Services\User\Checkout;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AddressService;
use App\Services\Shipping\ShippingService;
use App\Services\User\Cart\CartService;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    protected CartService $cartService;
    protected AddressService $addressService;
    protected ShippingService $shippingService;

    public function __construct(CartService $cartService, AddressService $addressService, ShippingService $shippingService)
    {
        $this->cartService = $cartService;
        $this->addressService = $addressService;
        $this->shippingService = $shippingService;
    }

    public function saveAddress($user, array $data): Cart
    {
        // create or update address via AddressService
        $address = $this->addressService->createForUser($user, $data);

        $cart = $this->cartService->getOrCreateActiveCart($user);
        $cart->address_id = $address->id;
        $cart->save();

        return $cart->load('items');
    }

    public function selectPaymentMethod($user, int $paymentMethodId): Cart
    {
        $cart = $this->cartService->getOrCreateActiveCart($user);
        $cart->payment_method_id = $paymentMethodId;
        $cart->save();
        return $cart->load('items');
    }

    public function summary($user): array
    {
        $cart = $this->cartService->getOrCreateActiveCart($user);

        $summary = $this->cartService->summarizeCart($cart);

        $address = $cart->address_id ? $this->addressService->findForUser($user, $cart->address_id) : null;

        $shippingFee = 0;
        if ($cart->vendor_id) {
            $vendor = $cart->vendor_id ? \App\Models\Vendor::find($cart->vendor_id) : null;
            if ($vendor) {
                $shippingFee = $this->shippingService->resolveShippingFee($vendor, $address);
            }
        }

        $vat = $cart->vat ?? 0;
        $total = $summary['subtotal'] + $shippingFee + $vat;

        return [
            'vendor_id' => $summary['vendor_id'] ?? null,
            'items' => $summary['items'],
            'address' => $address,
            'payment_method_id' => $cart->payment_method_id,
            'shipping_fee' => $shippingFee,
            'vat' => $vat,
            'subtotal' => $summary['subtotal'],
            'total' => $total,
        ];
    }

    public function confirm($user, ?string $note = null): Order
    {
        return DB::transaction(function () use ($user, $note) {
            $cart = $this->cartService->getOrCreateActiveCart($user);

            if ($cart->items->isEmpty()) {
                throw new \RuntimeException('cart_empty');
            }

            if (! $cart->address_id) {
                throw new \RuntimeException('address_required');
            }

            if (! $cart->payment_method_id) {
                throw new \RuntimeException('payment_required');
            }

            $address = $this->addressService->findForUser($user, $cart->address_id);
            $vendor = \App\Models\Vendor::find($cart->vendor_id);

            $shippingFee = $this->shippingService->resolveShippingFee($vendor, $address);
            $subtotal = $this->cartService->summarizeCart($cart)['subtotal'];
            $vat = $cart->vat ?? 0;
            $total = $subtotal + $shippingFee + $vat;

            $order = Order::create([
                'user_id' => $user->id,
                'vendor_id' => $cart->vendor_id,
                'address_id' => $cart->address_id,
                'payment_method_id' => $cart->payment_method_id,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'vat' => $vat,
                'total' => $total,
                'status' => 'pending',
                'note' => $note,
            ]);

            foreach ($cart->items as $ci) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'vendor_item_id' => $ci->vendor_item_id,
                    'vendor_id' => $ci->vendor_id,
                    'quantity' => $ci->quantity,
                    'unit_price' => $ci->unit_price,
                    'line_total' => $ci->quantity * $ci->unit_price,
                ]);
            }

            // mark cart checked out and remove items
            $cart->status = 'checked_out';
            $cart->shipping_fee = $shippingFee;
            $cart->vat = $vat;
            $cart->save();

            // delete cart items
            foreach ($cart->items as $ci) { $ci->delete(); }

            return $order->load('items');
        });
    }
}
