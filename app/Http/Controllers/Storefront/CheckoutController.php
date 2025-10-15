<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index(CartService $cart)
    {
        if ($cart->items()->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Add items to your cart before checking out.');
        }

        return view('storefront.checkout.index', [
            'items' => $cart->items(),
            'totals' => $cart->totals(),
            'coupon' => $cart->coupon(),
            'user' => Auth::user(),
        ]);
    }

    public function store(Request $request, CartService $cart)
    {
        $items = $cart->items();

        if ($items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer.name' => ['required', 'string', 'max:120'],
            'customer.email' => ['required', 'email'],
            'customer.phone' => ['nullable', 'string', 'max:30'],
            'billing.line1' => ['required', 'string', 'max:150'],
            'billing.city' => ['required', 'string', 'max:80'],
            'billing.state' => ['required', 'string', 'max:80'],
            'billing.postcode' => ['required', 'string', 'max:10'],
            'billing.country' => ['required', 'string', 'max:80'],
            'shipping_same' => ['nullable', 'boolean'],
            'shipping.line1' => ['required_if:shipping_same,false', 'nullable', 'string', 'max:150'],
            'shipping.city' => ['required_if:shipping_same,false', 'nullable', 'string', 'max:80'],
            'shipping.state' => ['required_if:shipping_same,false', 'nullable', 'string', 'max:80'],
            'shipping.postcode' => ['required_if:shipping_same,false', 'nullable', 'string', 'max:10'],
            'shipping.country' => ['required_if:shipping_same,false', 'nullable', 'string', 'max:80'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:card,bank,afterpay'],
        ]);

        $totals = $cart->totals();
        $coupon = $cart->coupon();

        $shippingAddress = $request->boolean('shipping_same')
            ? $validated['billing']
            : ($validated['shipping'] ?? $validated['billing']);

        $order = DB::transaction(function () use ($validated, $items, $totals, $coupon, $shippingAddress) {
            $order = Order::create([
                'user_id' => optional(Auth::user())->id,
                'email' => $validated['customer']['email'],
                'phone' => $validated['customer']['phone'] ?? null,
                'customer_name' => $validated['customer']['name'],
                'status' => 'processing',
                'payment_status' => 'paid',
                'payment_method' => $validated['payment_method'],
                'currency' => 'AUD',
                'subtotal' => $totals['subtotal'],
                'discount_total' => $totals['discount'],
                'tax_total' => $totals['tax'],
                'shipping_total' => $totals['shipping'],
                'grand_total' => $totals['total'],
                'billing_address' => $validated['billing'],
                'shipping_address' => $shippingAddress,
                'coupon_code' => optional($coupon)->code,
                'placed_at' => now(),
                'paid_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                /** @var Product $product */
                $product = $item['product'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'discount_total' => 0,
                    'line_total' => $product->price * $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        Session::put('checkout.last_order', $order->id);
        $cart->clear();

        return redirect()->route('checkout.complete', $order)->with('success', 'Order placed successfully!');
    }

    public function complete(Order $order)
    {
        if (Session::pull('checkout.last_order') !== $order->id) {
            return redirect()->route('home');
        }

        return view('storefront.checkout.complete', [
            'order' => $order->load('items'),
        ]);
    }
}
