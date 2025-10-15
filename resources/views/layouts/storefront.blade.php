<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Vape Commerce'))</title>
        <meta name="description" content="@yield('meta_description', 'Modern vape ecommerce built with Laravel')">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;space-grotesk:400,500,600&display=swap" rel="stylesheet" />

        @php($viteManifest = public_path('build/manifest.json'))
        @if(app()->environment('testing') && ! file_exists($viteManifest))
            <!-- Vite manifest skipped in testing -->
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-slate-950 text-slate-100 font-sans">
        <header class="border-b border-white/10 bg-gradient-to-r from-purple-900/80 via-slate-900 to-slate-950">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="bg-gradient-to-r from-cyan-400 to-violet-500 text-slate-900 font-black px-3 py-2 rounded-full uppercase tracking-widest text-xs">VAP</span>
                    <div>
                        <p class="text-2xl font-semibold tracking-tight">Vaperoo Commerce</p>
                        <p class="text-xs text-slate-300">Premium AU vape marketplace</p>
                    </div>
                </a>
                <div class="flex-1 md:px-8">
                    <form action="{{ route('products.index') }}" method="get" class="relative">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search flavours, brands or strengths..." class="w-full rounded-full border-0 bg-slate-800/80 py-3 pl-5 pr-14 text-sm text-slate-100 placeholder:text-slate-400 focus:ring-2 focus:ring-cyan-400" />
                        <button class="absolute right-1 top-1/2 -translate-y-1/2 rounded-full bg-cyan-500 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-900">Search</button>
                    </form>
                </div>
                <nav class="flex items-center gap-4 text-sm">
                    <a href="{{ route('products.index', ['sort' => 'featured']) }}" class="hover:text-cyan-300">Shop</a>
                    <a href="{{ route('products.index', ['category' => 'disposables']) }}" class="hover:text-cyan-300">Disposables</a>
                    <a href="{{ route('products.index', ['category' => 'nic-salts']) }}" class="hover:text-cyan-300">Nic Salts</a>
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-1 hover:text-cyan-300">
                        <span>Cart</span>
                        <span class="inline-flex items-center justify-center rounded-full bg-cyan-500 px-2 py-0.5 text-xs font-semibold text-slate-900">{{ session('cart.items') ? count(session('cart.items')) : 0 }}</span>
                    </a>
                    @auth
                        <a href="{{ route('account.index') }}" class="hover:text-cyan-300">Account</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-cyan-300">Admin</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hover:text-cyan-300">Sign In</a>
                    @endauth
                </nav>
            </div>
        </header>

        @include('partials.flash')

        <main class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 pb-20">
            @yield('hero')
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                @yield('content')
            </div>
        </main>

        <footer class="border-t border-white/10 bg-slate-950/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid gap-6 md:grid-cols-4 text-sm text-slate-400">
                <div>
                    <p class="text-slate-100 font-semibold">Why Vaperoo?</p>
                    <p class="mt-2 text-slate-400">Curated AU compliant devices, flavours and accessories tailored for discerning vapers.</p>
                </div>
                <div>
                    <p class="text-slate-100 font-semibold">Support</p>
                    <ul class="mt-2 space-y-2">
                        <li><a href="#" class="hover:text-cyan-300">Shipping & Returns</a></li>
                        <li><a href="#" class="hover:text-cyan-300">Age Verification</a></li>
                        <li><a href="#" class="hover:text-cyan-300">Wholesale Enquiries</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-slate-100 font-semibold">Stay in the loop</p>
                    <form class="mt-2 space-y-3">
                        <input type="email" placeholder="Email address" class="w-full rounded-md bg-slate-900/80 border border-white/10 px-3 py-2 text-sm" />
                        <button type="button" class="w-full rounded-md bg-cyan-500 px-3 py-2 text-sm font-semibold text-slate-900">Subscribe</button>
                    </form>
                </div>
                <div>
                    <p class="text-slate-100 font-semibold">Follow us</p>
                    <div class="mt-3 flex gap-3 text-lg">
                        <a href="#" class="hover:text-cyan-300">IG</a>
                        <a href="#" class="hover:text-cyan-300">TT</a>
                        <a href="#" class="hover:text-cyan-300">YT</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/5 py-4 text-center text-xs text-slate-500">&copy; {{ now()->year }} Vaperoo Commerce. All rights reserved.</div>
        </footer>
    </body>
</html>
