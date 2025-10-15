@extends('layouts.storefront')

@section('title', 'Order confirmed | Vaperoo Commerce')

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/5 bg-slate-900/60 p-8 text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-300">
            ✓
        </div>
        <h1 class="mt-6 text-3xl font-semibold text-white">Thanks for your order!</h1>
        <p class="mt-4 text-sm text-slate-300">Order number <span class="font-semibold text-white">{{ $order->order_number }}</span> has been received. We’ve sent a confirmation to {{ $order->email }}.</p>
        <div class="mt-8 text-left">
            <h2 class="text-lg font-semibold text-white">Order summary</h2>
            <ul class="mt-4 space-y-2 text-sm text-slate-300">
                @foreach($order->items as $item)
                    <li class="flex justify-between">
                        <span>{{ $item->quantity }} × {{ $item->product_name }}</span>
                        <span>${{ number_format($item->line_total, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <dl class="mt-6 space-y-2 text-sm text-slate-300">
                <div class="flex justify-between"><dt>Subtotal</dt><dd>${{ number_format($order->subtotal, 2) }}</dd></div>
                <div class="flex justify-between"><dt>Tax</dt><dd>${{ number_format($order->tax_total, 2) }}</dd></div>
                <div class="flex justify-between"><dt>Shipping</dt><dd>${{ number_format($order->shipping_total, 2) }}</dd></div>
                @if($order->discount_total > 0)
                    <div class="flex justify-between text-emerald-300"><dt>Discount</dt><dd>- ${{ number_format($order->discount_total, 2) }}</dd></div>
                @endif
                <div class="flex justify-between text-lg font-semibold text-white"><dt>Total paid</dt><dd>${{ number_format($order->grand_total, 2) }}</dd></div>
            </dl>
        </div>
        <div class="mt-8 flex flex-wrap justify-center gap-3 text-sm">
            <a href="{{ route('products.index') }}" class="rounded-full bg-cyan-500 px-6 py-3 font-semibold text-slate-900">Continue shopping</a>
            @auth
                <a href="{{ route('account.index') }}" class="rounded-full border border-white/20 px-6 py-3 font-semibold text-white">View account</a>
            @endauth
        </div>
    </div>
@endsection
