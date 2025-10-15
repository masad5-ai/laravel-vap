@extends('layouts.storefront')

@section('title', 'Shop | Vaperoo Commerce')

@section('content')
    <div class="grid gap-10 lg:grid-cols-[280px_1fr]">
        <aside class="space-y-6">
            <form method="get" class="space-y-4 rounded-3xl border border-white/5 bg-slate-900/60 p-6">
                <div>
                    <label class="text-sm font-semibold text-white">Search</label>
                    <input type="search" name="q" value="{{ $filters['q'] }}" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                </div>
                <div>
                    <label class="text-sm font-semibold text-white">Category</label>
                    <select name="category" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white">
                        <option value="">All</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected($filters['category'] === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs uppercase tracking-wide text-slate-400">Min price</label>
                        <input type="number" step="0.5" name="min_price" value="{{ $filters['min_price'] }}" class="mt-1 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                    <div>
                        <label class="text-xs uppercase tracking-wide text-slate-400">Max price</label>
                        <input type="number" step="0.5" name="max_price" value="{{ $filters['max_price'] }}" class="mt-1 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white" />
                    </div>
                </div>
                <div>
                    <label class="text-sm font-semibold text-white">Sort</label>
                    <select name="sort" class="mt-2 w-full rounded-lg border border-white/10 bg-slate-900/60 px-3 py-2 text-sm text-white">
                        <option value="name" @selected($filters['sort'] === 'name')>Alphabetical</option>
                        <option value="featured" @selected($filters['sort'] === 'featured')>Featured</option>
                        <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Price: Low to high</option>
                        <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Price: High to low</option>
                        <option value="newest" @selected($filters['sort'] === 'newest')>Newest</option>
                    </select>
                </div>
                <button class="w-full rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-slate-900">Apply filters</button>
            </form>
        </aside>
        <section>
            <div class="flex items-center justify-between text-sm text-slate-400">
                <p>{{ $products->total() }} products</p>
                <a href="{{ route('products.index') }}" class="text-cyan-300">Reset</a>
            </div>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach($products as $product)
                    @include('storefront.products.partials.card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </section>
    </div>
@endsection
