@extends('layouts.storefront')

@section('title', $product->name.' | Vaperoo Commerce')

@section('content')
    <div class="grid gap-12 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($product->images->take(4) as $image)
                    <img src="{{ $image->path }}" alt="{{ $image->alt_text ?? $product->name }}" class="rounded-3xl border border-white/5 object-cover" />
                @endforeach
                @if($product->images->isEmpty())
                    <img src="{{ $product->hero_image ?? 'https://images.unsplash.com/photo-1524593984081-fff0b3bb83ce?auto=format&fit=crop&w=800&q=60' }}" alt="{{ $product->name }}" class="rounded-3xl border border-white/5 object-cover" />
                @endif
            </div>
            <div class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                <h2 class="text-xl font-semibold text-white">Product story</h2>
                <div class="mt-4 prose prose-invert max-w-none">
                    {!! nl2br(e($product->description ?? $product->short_description)) !!}
                </div>
            </div>
        </div>
        <div class="space-y-8">
            <div class="rounded-3xl border border-white/5 bg-slate-900/60 p-8">
                <p class="text-sm uppercase tracking-widest text-cyan-300">{{ $product->brand ?? 'Curated' }}</p>
                <h1 class="mt-3 text-3xl font-semibold text-white">{{ $product->name }}</h1>
                <p class="mt-2 text-sm text-slate-300">{{ $product->short_description }}</p>
                <div class="mt-6 flex items-baseline gap-3">
                    <span class="text-3xl font-bold text-white">${{ number_format($product->price, 2) }}</span>
                    @if($product->compare_at_price)
                        <span class="text-sm text-slate-500 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                    @endif
                </div>
                <p class="mt-3 text-xs uppercase tracking-widest text-slate-400">{{ $product->nicotine_strength }} • {{ $product->flavour_profile }}</p>
                <p class="mt-1 text-xs text-slate-400">Stock level: {{ $product->stock > 0 ? $product->stock.' units' : 'Out of stock' }}</p>

                <form action="{{ route('cart.store', $product) }}" method="post" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm text-slate-200">Quantity</label>
                        <input type="number" min="1" value="1" name="quantity" class="mt-2 w-32 rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <button class="w-full rounded-full bg-cyan-500 px-6 py-3 text-sm font-semibold text-slate-900 disabled:cursor-not-allowed disabled:bg-slate-500" @disabled(!$product->inStock())>Add to cart</button>
                </form>

                <div class="mt-6 grid grid-cols-2 gap-4 text-sm text-slate-300">
                    <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-4">
                        <p class="font-semibold text-white">PG/VG Ratio</p>
                        <p class="text-xs text-slate-400 mt-2">{{ data_get($product->attributes, 'pg_vg_ratio', '70/30') }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-4">
                        <p class="font-semibold text-white">Bottle size</p>
                        <p class="text-xs text-slate-400 mt-2">{{ data_get($product->attributes, 'bottle_size', '60ml') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                <h2 class="text-lg font-semibold text-white">Shipping & compliance</h2>
                <ul class="mt-4 space-y-2 text-sm text-slate-300">
                    <li>• Express Australia Post shipping available nationwide.</li>
                    <li>• Age verification and TGO 110 compliant labelling included.</li>
                    <li>• Cold chain storage and tamper-evident packaging.</li>
                </ul>
            </div>
        </div>
    </div>

    <section class="mt-16">
        <h2 class="text-2xl font-semibold text-white">You may also like</h2>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($relatedProducts as $related)
                @include('storefront.products.partials.card', ['product' => $related])
            @endforeach
        </div>
    </section>
@endsection
