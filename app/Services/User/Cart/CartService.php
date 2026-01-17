<?php

namespace App\Services\User\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Models\VendorItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getOrCreateActiveCart(User $user): Cart
    {
        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
            'status' => 'active',
        ], [
            'vendor_id' => null,
        ]);

        return $cart;
    }

    /**
     * Add item to user's active cart enforcing single-vendor rule
     * Returns the cart model
     */
    public function addItem(User $user, array $data): Cart
    {
        return DB::transaction(function () use ($user, $data) {
            $vendorItemId = (int) ($data['item_id'] ?? $data['vendor_item_id'] ?? 0);
            $quantity = (int) ($data['quantity'] ?? 1);

            $item = VendorItem::find($vendorItemId);
            if (! $item) throw new \InvalidArgumentException('item_not_found');

            if ($quantity < 1 || $quantity > 99) throw new \InvalidArgumentException('invalid_quantity');

            // check stock
            if ($item->quantity_available !== null && $item->quantity_available < $quantity) {
                throw new \InvalidArgumentException('out_of_stock');
            }

            $cart = $this->getOrCreateActiveCart($user);

            // enforce vendor rule
            if ($cart->vendor_id && $cart->vendor_id !== $item->vendor_id) {
                throw new \RuntimeException('vendor_mismatch:' . $cart->vendor_id . ':' . $item->vendor_id);
            }

            // if cart has no vendor yet, set it
            if (! $cart->vendor_id) {
                $cart->vendor_id = $item->vendor_id;
                $cart->save();
            }

            // add or update cart item
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('vendor_item_id', $vendorItemId)
                ->first();

            $unitPrice = (int) $item->price; // price stored as bigInteger

            if ($cartItem) {
                $newQty = min(99, $cartItem->quantity + $quantity);
                $cartItem->quantity = $newQty;
                $cartItem->unit_price = $unitPrice;
                $cartItem->save();
            } else {
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'vendor_item_id' => $vendorItemId,
                    'vendor_id' => $item->vendor_id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ]);
            }

            return $cart->load('items.vendorItem');
        });
    }

    public function summarizeCart(Cart $cart): array
    {
        $items = $cart->items->map(function ($ci) {
            $name = $ci->vendorItem?->name ?? null;
            $lineTotal = $ci->quantity * (int) $ci->unit_price;
            return [
                'id' => $ci->id,
                'vendor_item_id' => $ci->vendor_item_id,
                'name' => $name,
                'unit_price' => $ci->unit_price,
                'quantity' => $ci->quantity,
                'total_line' => $lineTotal,
            ];
        })->toArray();

        $subtotal = array_sum(array_column($items, 'total_line'));

        return [
            'cart_id' => $cart->id,
            'vendor_id' => $cart->vendor_id,
            'items_count' => count($items),
            'items' => $items,
            'subtotal' => $subtotal,
        ];
    }
}
