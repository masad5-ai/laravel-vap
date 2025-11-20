<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => ['required', 'string', 'max:255'],
        ]);

        $cartItems = Auth::user()->cartItems()->with('product')->get();
        abort_if($cartItems->isEmpty(), 400, 'Cart is empty');

        $total = $cartItems->sum(fn (CartItem $item) => $item->product->price * $item->quantity);

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'shipping_address' => $validated['shipping_address'],
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        CartItem::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.show', $order)->with('status', 'Order placed successfully');
    }
}
