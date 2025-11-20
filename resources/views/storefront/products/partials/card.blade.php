@php($image = $product->primaryImage()?->path ?? $product->hero_image ?? 'https://images.unsplash.com/photo-1524593984081-fff0b3bb83ce?auto=format&fit=crop&w=800&q=60')
<a href="{{ route('products.show', $product->slug) }}" class="group block overflow-hidden rounded-3xl border border-white/5 bg-slate-900/70 shadow-xl transition hover:-translate-y-1 hover:border-cyan-400/40">
    <div class="relative aspect-[4/5] bg-slate-800/60">
        <img src="{{ $image }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center transition duration-500 group-hover:scale-105" />
        <div class="absolute left-3 top-3 flex gap-2 text-[11px] font-semibold uppercase tracking-wide">
            @if($product->is_featured)
                <span class="rounded-full bg-white/90 px-3 py-1 text-slate-900">Featured</span>
            @endif
            <span class="rounded-full bg-cyan-500/90 px-3 py-1 text-slate-950">{{ $product->nicotine_strength ?? 'Smooth' }}</span>
        </div>
    </div>
    <div class="p-5 space-y-3">
        <div class="flex items-center justify-between text-xs uppercase tracking-[0.2em] text-slate-400">
            <span class="text-cyan-300">{{ $product->brand ?? 'Curated' }}</span>
            <span class="inline-flex items-center gap-1 text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="m12 17 6.16 3.71-1.64-7.03L21.5 9.9l-7.19-.61L12 3 9.69 9.29 2.5 9.9l4.98 3.78-1.64 7.03z"/></svg>
                4.8
            </span>
        </div>
        <p class="text-lg font-semibold text-white">{{ $product->name }}</p>
        <p class="text-sm text-slate-300">{{ \Illuminate\Support\Str::limit($product->short_description, 90) }}</p>
        <div class="flex items-baseline gap-3">
            <span class="text-xl font-bold text-white">${{ number_format($product->price, 2) }}</span>
            @if($product->compare_at_price)
                <span class="text-sm text-slate-500 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
            @endif
        </div>
        <p class="text-xs text-slate-400">{{ $product->flavour_profile ?? 'Smooth finish' }}</p>
    </div>
</a>
