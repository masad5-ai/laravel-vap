@php($image = $product->primaryImage()?->path ?? $product->hero_image ?? 'https://images.unsplash.com/photo-1524593984081-fff0b3bb83ce?auto=format&fit=crop&w=800&q=60')
<a href="{{ route('products.show', $product->slug) }}" class="group block overflow-hidden rounded-3xl border border-white/5 bg-slate-900/60 shadow-lg transition hover:-translate-y-1 hover:border-cyan-400/40">
    <div class="aspect-[4/5] bg-slate-800/60">
        <img src="{{ $image }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center transition group-hover:scale-105" />
    </div>
    <div class="p-5 space-y-3">
        <p class="text-xs uppercase tracking-widest text-cyan-300">{{ $product->brand ?? 'Curated' }}</p>
        <p class="text-lg font-semibold text-white">{{ $product->name }}</p>
        <p class="text-sm text-slate-300">{{ \Illuminate\Support\Str::limit($product->short_description, 90) }}</p>
        <div class="flex items-baseline gap-3">
            <span class="text-xl font-bold text-white">${{ number_format($product->price, 2) }}</span>
            @if($product->compare_at_price)
                <span class="text-sm text-slate-500 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
            @endif
        </div>
        <p class="text-xs text-slate-400">{{ $product->nicotine_strength }} • {{ $product->flavour_profile }}</p>
    </div>
</a>
