<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();

        $subtotal = $cartItems->sum(fn (CartItem $item) => $item->product->price * $item->quantity);

        return view('shop.cart', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['sometimes', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'] ?? 1;

        $item = CartItem::query()
            ->where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('status', 'Added to cart');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorizeCartItem($cartItem);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->update($validated);

        return back()->with('status', 'Cart updated');
    }

    public function destroy(CartItem $cartItem)
    {
        $this->authorizeCartItem($cartItem);
        $cartItem->delete();

        return back()->with('status', 'Item removed');
    }

    protected function authorizeCartItem(CartItem $cartItem): void
    {
        abort_if($cartItem->user_id !== Auth::id(), 403);
    }
}
