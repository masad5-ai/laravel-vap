@extends('layouts.storefront')

@section('title', 'Cart | Vaperoo Commerce')

@section('content')
    <h1 class="text-3xl font-semibold text-white">Your cart</h1>

    <div class="mt-8 grid gap-10 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        <div class="space-y-6">
            @forelse($items as $item)
                <div class="flex flex-col gap-6 rounded-3xl border border-white/5 bg-slate-900/60 p-6 sm:flex-row sm:items-center">
                    <img src="{{ $item['product']->primaryImage()?->path ?? $item['product']->hero_image ?? 'https://images.unsplash.com/photo-1524593984081-fff0b3bb83ce?auto=format&fit=crop&w=400&q=60' }}" class="h-28 w-28 rounded-2xl object-cover" alt="{{ $item['product']->name }}" />
                    <div class="flex-1 space-y-2">
                        <p class="text-sm uppercase tracking-widest text-cyan-300">{{ $item['product']->brand }}</p>
                        <a href="{{ route('products.show', $item['product']->slug) }}" class="block text-lg font-semibold text-white">{{ $item['product']->name }}</a>
                        <p class="text-sm text-slate-300">{{ $item['product']->nicotine_strength }} • {{ $item['product']->flavour_profile }}</p>
                        <div class="flex items-center gap-4 text-sm text-slate-400">
                            <form method="post" action="{{ route('cart.update', $item['product']) }}" class="flex items-center gap-2">
                                @csrf
                                @method('put')
                                <input type="number" min="0" name="quantity" value="{{ $item['quantity'] }}" class="w-20 rounded-lg border border-white/10 bg-slate-900/60 px-2 py-1 text-sm text-white" />
                                <button class="rounded-full bg-cyan-500 px-3 py-1 text-xs font-semibold text-slate-900">Update</button>
                            </form>
                            <form method="post" action="{{ route('cart.destroy', $item['product']) }}">
                                @csrf
                                @method('delete')
                                <button class="text-xs uppercase tracking-wide text-rose-300">Remove</button>
                            </form>
                        </div>
                    </div>
                    <div class="text-right text-lg font-semibold text-white">
                        ${{ number_format($item['line_total'], 2) }}
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-white/10 bg-slate-900/40 p-12 text-center text-slate-400">
                    Your cart is currently empty.
                </div>
            @endforelse
        </div>
        <div class="space-y-6">
            <div class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                <h2 class="text-xl font-semibold text-white">Order summary</h2>
                <dl class="mt-4 space-y-3 text-sm text-slate-300">
                    <div class="flex justify-between"><dt>Subtotal</dt><dd>${{ number_format($totals['subtotal'], 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Shipping</dt><dd>${{ number_format($totals['shipping'], 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Tax (10%)</dt><dd>${{ number_format($totals['tax'], 2) }}</dd></div>
                    <div class="flex justify-between text-emerald-300"><dt>Discount</dt><dd>- ${{ number_format($totals['discount'], 2) }}</dd></div>
                </dl>
                <div class="mt-4 flex items-center justify-between text-lg font-semibold text-white">
                    <span>Total</span>
                    <span>${{ number_format($totals['total'], 2) }}</span>
                </div>
                <form method="post" action="{{ route('cart.apply-coupon') }}" class="mt-6 flex gap-2">
                    @csrf
                    <input type="text" name="code" value="{{ $coupon?->code }}" placeholder="Coupon code" class="flex-1 rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    <button class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white">Apply</button>
                </form>
                <a href="{{ route('checkout.index') }}" class="mt-6 block rounded-full bg-cyan-500 px-6 py-3 text-center text-sm font-semibold text-slate-900 @if($items->isEmpty()) opacity-50 cursor-not-allowed @endif">Proceed to checkout</a>
                <form method="post" action="{{ route('cart.clear') }}" class="mt-3 text-center text-xs text-slate-400">
                    @csrf
                    @method('delete')
                    <button>Clear cart</button>
                </form>
            </div>
        </div>
    </div>
@endsection
