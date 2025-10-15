@extends('layouts.storefront')

@section('title', 'Home | Vaperoo Commerce')

@section('hero')
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-purple-600/80 via-cyan-500/80 to-slate-900 shadow-2xl">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.2),transparent_60%)]"></div>
        <div class="relative max-w-5xl px-8 py-16">
            <p class="inline-flex items-center rounded-full bg-black/20 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-white/80">Nicotine compliant • Same day dispatch</p>
            <h1 class="mt-6 text-4xl sm:text-5xl font-semibold leading-tight text-white">An elevated vape marketplace inspired by Australia’s favourite retailers.</h1>
            <p class="mt-4 max-w-2xl text-lg text-white/80">Discover disposable pods, nic salts and premium e-liquids curated from Vaperoo, Uncle V and Vices. Built with Laravel 12, Tailwind and a headless-ready architecture.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-slate-900/20">Browse catalogue</a>
                <a href="{{ route('register') }}" class="rounded-full border border-white/60 px-6 py-3 text-sm font-semibold text-white/80 hover:bg-white/10">Create wholesale account</a>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="grid gap-6 md:grid-cols-3">
        @foreach($valueProps as $prop)
            <div class="rounded-2xl border border-white/5 bg-slate-900/70 p-6">
                <p class="text-sm font-semibold uppercase tracking-widest text-cyan-300">{{ $prop['title'] }}</p>
                <p class="mt-3 text-sm text-slate-300">{{ $prop['copy'] }}</p>
            </div>
        @endforeach
    </section>

    <section class="mt-14">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-white">Featured drops</h2>
            <a href="{{ route('products.index', ['sort' => 'featured']) }}" class="text-sm text-cyan-300">View all</a>
        </div>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($featuredProducts as $product)
                @include('storefront.products.partials.card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <section class="mt-16">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold text-white">New arrivals</h2>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="text-sm text-cyan-300">Shop new</a>
        </div>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($newArrivals as $product)
                @include('storefront.products.partials.card', ['product' => $product])
            @endforeach
        </div>
    </section>

    <section class="mt-16">
        <h2 class="text-2xl font-semibold text-white">Shop by category</h2>
        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($topCategories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group relative overflow-hidden rounded-3xl border border-white/5 bg-slate-900/70 p-8 shadow-lg">
                    <div class="absolute inset-0 bg-cover bg-center opacity-20 group-hover:opacity-40 transition" style="background-image: url('{{ $category->hero_image ?? 'https://images.unsplash.com/photo-1524593984081-fff0b3bb83ce?auto=format&fit=crop&w=800&q=60' }}')"></div>
                    <div class="relative">
                        <p class="text-sm uppercase tracking-widest text-cyan-300">{{ $category->name }}</p>
                        <p class="mt-2 text-sm text-slate-200">{{ \Illuminate\Support\Str::limit($category->description, 120) }}</p>
                        <div class="mt-4 flex flex-wrap gap-1 text-xs text-slate-300">
                            @foreach($category->products as $product)
                                <span class="rounded-full bg-slate-900/80 px-3 py-1">{{ $product->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
