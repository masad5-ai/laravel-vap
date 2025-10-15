<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(CartService $cart)
    {
        return view('storefront.cart.index', [
            'items' => $cart->items(),
            'totals' => $cart->totals(),
            'coupon' => $cart->coupon(),
        ]);
    }

    public function store(Request $request, CartService $cart, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if (! $product->inStock()) {
            return back()->with('error', 'This product is currently out of stock.');
        }

        $cart->add($product, (int) $validated['quantity']);

        return back()->with('success', "{$product->name} added to your cart.");
    }

    public function update(Request $request, CartService $cart, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart->update($product, (int) $validated['quantity']);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function destroy(CartService $cart, Product $product)
    {
        $cart->remove($product);

        return back()->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request, CartService $cart)
    {
        $validated = $request->validate([
            'code' => ['nullable', 'string'],
        ]);

        $coupon = $cart->applyCoupon($validated['code'] ?? null);

        return back()->with(
            $coupon ? 'success' : 'error',
            $coupon ? "Coupon {$coupon->code} applied." : 'Coupon unavailable or expired.'
        );
    }

    public function clear(CartService $cart)
    {
        $cart->clear();

        return back()->with('success', 'Cart cleared.');
    }
}
