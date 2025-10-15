<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\Integration\IntegrationEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            'paymentIntegrations' => Integration::active()->forType(Integration::TYPE_PAYMENT)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, CartService $cart, IntegrationEngine $integrationEngine)
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
            'payment_integration_id' => ['required', Rule::exists('integrations', 'id')->where(fn ($query) => $query->where('type', Integration::TYPE_PAYMENT)->where('is_active', true))],
        ]);

        $totals = $cart->totals();
        $coupon = $cart->coupon();

        $shippingAddress = $request->boolean('shipping_same')
            ? $validated['billing']
            : ($validated['shipping'] ?? $validated['billing']);

        /** @var Integration $paymentIntegration */
        $paymentIntegration = Integration::active()
            ->forType(Integration::TYPE_PAYMENT)
            ->findOrFail($validated['payment_integration_id']);

        $paymentReference = 'CHK-'.Str::upper(Str::random(10));

        $paymentResult = $integrationEngine->processPayment($paymentIntegration, [
            'order_reference' => $paymentReference,
            'amount' => $totals['total'],
            'currency' => 'AUD',
            'customer_name' => $validated['customer']['name'],
            'customer_email' => $validated['customer']['email'],
            'customer_phone' => $validated['customer']['phone'] ?? null,
            'billing_address' => $validated['billing'],
            'shipping_address' => $shippingAddress,
            'notes' => $validated['notes'] ?? null,
            'items' => $items->map(function ($item) {
                /** @var Product $product */
                $product = $item['product'];

                return [
                    'product_id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                ];
            })->values()->all(),
        ]);

        if (! $paymentResult['success']) {
            return back()->withInput()->with('error', $paymentResult['message'] ?? 'Unable to process payment. Please try another method.');
        }

        $order = DB::transaction(function () use ($validated, $items, $totals, $coupon, $shippingAddress, $paymentIntegration, $paymentResult) {
            $order = Order::create([
                'user_id' => optional(Auth::user())->id,
                'email' => $validated['customer']['email'],
                'phone' => $validated['customer']['phone'] ?? null,
                'customer_name' => $validated['customer']['name'],
                'status' => 'processing',
                'payment_status' => 'paid',
                'payment_method' => $paymentIntegration->name,
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
                'meta' => [
                    'payment' => $paymentResult,
                ],
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

        $notificationPayload = [
            'order_number' => $order->order_number,
            'amount' => $order->grand_total,
            'currency' => $order->currency,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->email,
            'customer_phone' => $order->phone,
            'payment_method' => $order->payment_method,
        ];

        $notifications = [
            'email' => $integrationEngine->executeForType(Integration::TYPE_EMAIL, 'order.confirmation', $notificationPayload),
            'sms' => $integrationEngine->executeForType(Integration::TYPE_SMS, 'order.confirmation', $notificationPayload),
            'whatsapp' => $integrationEngine->executeForType(Integration::TYPE_WHATSAPP, 'order.confirmation', $notificationPayload),
        ];

        $order->update(['meta' => array_merge($order->meta ?? [], ['notifications' => $notifications])]);

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
