@extends('layouts.storefront')

@section('title', 'Home | Vaperoo Commerce')

@section('hero')
    <section class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 shadow-2xl">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.06),transparent_55%)]"></div>
        <div class="absolute right-0 top-0 hidden h-full w-1/2 bg-[url('https://images.unsplash.com/photo-1524594154901-8d1d1fbc7c07?auto=format&fit=crop&w=1600&q=80')] bg-cover bg-center opacity-50 lg:block"></div>
        <div class="relative grid items-center gap-10 px-8 py-14 lg:grid-cols-2 lg:px-14">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-cyan-200">
                    New season flavours • AU compliant
                </div>
                <h1 class="text-4xl font-semibold leading-tight text-white sm:text-5xl">A premium vape destination inspired by Vaperoo, Uncle V and Vices.</h1>
                <p class="text-lg text-slate-200">Drop-ready disposables, nic salts and hardware curated from top AU retailers. Built on Laravel 12 with full admin control over roles, products, gateways and notifications.</p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ route('products.index') }}" class="rounded-full bg-gradient-to-r from-cyan-400 to-violet-500 px-7 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/30 transition hover:translate-y-0.5">Shop the collection</a>
                    <a href="{{ route('cart.index') }}" class="rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-white/90 hover:border-cyan-300 hover:text-cyan-200">View cart</a>
                </div>
                <div class="grid gap-4 sm:grid-cols-3 pt-6">
                    <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-4 shadow-inner">
                        <p class="text-xs uppercase tracking-[0.2em] text-cyan-300">Dispatch</p>
                        <p class="mt-2 text-lg font-semibold text-white">Same day AU</p>
                        <p class="text-xs text-slate-400">Before 4pm AEST</p>
                    </div>
                    <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-4 shadow-inner">
                        <p class="text-xs uppercase tracking-[0.2em] text-cyan-300">Payments</p>
                        <p class="mt-2 text-lg font-semibold text-white">Secure & Custom</p>
                        <p class="text-xs text-slate-400">Admin-configured gateways</p>
                    </div>
                    <div class="rounded-2xl border border-white/5 bg-slate-900/60 p-4 shadow-inner">
                        <p class="text-xs uppercase tracking-[0.2em] text-cyan-300">Support</p>
                        <p class="mt-2 text-lg font-semibold text-white">Email • SMS • WA</p>
                        <p class="text-xs text-slate-400">Multi-channel alerts</p>
                    </div>
                </div>
            </div>
            <div class="relative hidden lg:block">
                <div class="absolute -left-12 -top-12 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl"></div>
                <div class="absolute -right-10 bottom-0 h-64 w-64 rounded-full bg-violet-500/20 blur-3xl"></div>
                <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 shadow-2xl">
                    <div class="p-6 pb-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-200">Trending bundle</p>
                        <p class="mt-2 text-2xl font-semibold text-white">Mixed Fruit Sampler</p>
                        <p class="mt-2 text-sm text-slate-300">Best-selling trio with chilled mango, peach ice and grape frost.</p>
                    </div>
                    <div class="grid grid-cols-3 gap-1">
                        <img src="https://images.unsplash.com/photo-1524594154901-8d1d1fbc7c07?auto=format&fit=crop&w=900&q=80" class="h-44 w-full object-cover" alt="Sampler" />
                        <img src="https://images.unsplash.com/photo-1510017803434-a899398421b8?auto=format&fit=crop&w=900&q=80" class="h-44 w-full object-cover" alt="Sampler" />
                        <img src="https://images.unsplash.com/photo-1524593984081-fff0b3bb83ce?auto=format&fit=crop&w=900&q=80" class="h-44 w-full object-cover" alt="Sampler" />
                    </div>
                    <div class="flex items-center justify-between p-6 text-sm">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-cyan-200">Limited</p>
                            <p class="text-lg font-semibold text-white">$35.00</p>
                        </div>
                        <a href="{{ route('products.index', ['sort' => 'featured']) }}" class="rounded-full bg-white px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-900">Shop drop</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="grid gap-6 md:grid-cols-3">
        @foreach($valueProps as $prop)
            <div class="rounded-2xl border border-white/5 bg-slate-900/70 p-6 shadow-lg shadow-slate-900/30">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cyan-300">{{ $prop['title'] }}</p>
                <p class="mt-3 text-sm text-slate-200">{{ $prop['copy'] }}</p>
            </div>
        @endforeach
    </section>

    <section class="mt-14 space-y-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-cyan-200">Editor’s picks</p>
                <h2 class="text-2xl font-semibold text-white">Featured drops</h2>
            </div>
            <a href="{{ route('products.index', ['sort' => 'featured']) }}" class="text-sm text-cyan-300">View all</a>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($featuredProducts as $product)
                @include('storefront.products.partials.card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <section class="mt-16 space-y-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-cyan-200">Fresh flavours</p>
                <h2 class="text-2xl font-semibold text-white">New arrivals</h2>
            </div>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-sm text-cyan-300">Shop new</a>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($newArrivals as $product)
                @include('storefront.products.partials.card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <section class="mt-16 space-y-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-cyan-200">Browse by flavour</p>
                <h2 class="text-2xl font-semibold text-white">Shop by category</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm text-cyan-300">Explore catalogue</a>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($topCategories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group relative overflow-hidden rounded-3xl border border-white/5 bg-slate-900/70 p-8 shadow-xl">
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-900/70 to-slate-950/70"></div>
                    <div class="absolute inset-0 bg-cover bg-center opacity-25 transition duration-300 group-hover:opacity-50" style="background-image: url('{{ $category->hero_image ?? 'https://images.unsplash.com/photo-1524593984081-fff0b3bb83ce?auto=format&fit=crop&w=800&q=60' }}')"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-cyan-300">{{ $category->name }}</p>
                        <p class="mt-2 text-sm text-slate-200">{{ \Illuminate\Support\Str::limit($category->description, 120) }}</p>
                        <div class="mt-4 flex flex-wrap gap-2 text-[11px] text-slate-100">
                            @foreach($category->products as $product)
                                <span class="rounded-full bg-slate-900/90 px-3 py-1">{{ $product->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
