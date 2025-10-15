<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function items(): Collection
    {
        $items = Session::get('cart.items', []);

        return collect($items)->map(function ($item) {
            $product = Product::find($item['product_id']);

            if (! $product) {
                return null;
            }

            $quantity = min($item['quantity'], max($product->stock, 0));

            return [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $product->price * $quantity,
            ];
        })->filter();
    }

    public function add(Product $product, int $quantity = 1): void
    {
        if ($product->stock <= 0) {
            return;
        }

        $cart = Session::get('cart.items', []);
        $key = (string) $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
            ];
        }

        $cart[$key]['quantity'] = max(1, min($cart[$key]['quantity'], $product->stock));

        Session::put('cart.items', $cart);
    }

    public function update(Product $product, int $quantity): void
    {
        $cart = Session::get('cart.items', []);
        $key = (string) $product->id;

        if ($quantity <= 0) {
            unset($cart[$key]);
        } else {
            if ($product->stock <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key] = [
                    'product_id' => $product->id,
                    'quantity' => min($quantity, $product->stock),
                ];
            }
        }

        Session::put('cart.items', $cart);
    }

    public function remove(Product $product): void
    {
        $cart = Session::get('cart.items', []);
        unset($cart[(string) $product->id]);
        Session::put('cart.items', $cart);
    }

    public function clear(): void
    {
        Session::forget(['cart.items', 'cart.coupon']);
    }

    public function applyCoupon(?string $code): ?Coupon
    {
        if (! $code) {
            Session::forget('cart.coupon');

            return null;
        }

        $coupon = Coupon::where('code', strtoupper($code))->first();

        if ($coupon && $coupon->isCurrentlyActive()) {
            Session::put('cart.coupon', $coupon->code);

            return $coupon;
        }

        Session::forget('cart.coupon');

        return null;
    }

    public function coupon(): ?Coupon
    {
        $code = Session::get('cart.coupon');

        if (! $code) {
            return null;
        }

        return Coupon::where('code', $code)->first();
    }

    public function totals(): array
    {
        $items = $this->items();
        $subtotal = $items->sum('line_total');
        $tax = round($subtotal * 0.1, 2);
        $shipping = $subtotal >= 120 ? 0 : 12.95;
        $coupon = $this->coupon();
        $discount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => $tax,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => round($subtotal + $tax + $shipping - $discount, 2),
        ];
    }
}
