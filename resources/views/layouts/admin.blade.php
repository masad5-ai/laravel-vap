<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Admin | '.config('app.name'))</title>
        @php($viteManifest = public_path('build/manifest.json'))
        @if(app()->environment('testing') && ! file_exists($viteManifest))
            <!-- Vite manifest skipped in testing -->
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-slate-100 text-slate-900">
        <div class="min-h-screen flex">
            <aside class="hidden md:flex md:w-64 flex-col bg-slate-900 text-slate-100">
                <div class="px-6 py-6 text-xl font-semibold tracking-tight">Vaperoo Admin</div>
                <nav class="flex-1 px-4 space-y-1 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.orders.index') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800' : '' }}">Orders</a>
                    <a href="{{ route('admin.products.index') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.products.*') ? 'bg-slate-800' : '' }}">Products</a>
                    <a href="{{ route('admin.categories.index') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.categories.*') ? 'bg-slate-800' : '' }}">Categories</a>
                    <a href="{{ route('admin.coupons.index') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.coupons.*') ? 'bg-slate-800' : '' }}">Coupons</a>
                    @if(auth()->user()->hasPermission(['manage-integrations', 'manage-roles']))
                        <div class="pt-3 text-xs uppercase tracking-wide text-slate-400">Configuration</div>
                    @endif
                    @if(auth()->user()->hasPermission('manage-integrations'))
                        <a href="{{ route('admin.integrations.index') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.integrations.*') ? 'bg-slate-800' : '' }}">Gateways</a>
                    @endif
                    @if(auth()->user()->hasPermission('manage-roles'))
                        <a href="{{ route('admin.roles.index') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.roles.*') ? 'bg-slate-800' : '' }}">Roles</a>
                        <a href="{{ route('admin.permissions.index') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.permissions.*') ? 'bg-slate-800' : '' }}">Permissions</a>
                        <a href="{{ route('admin.users.index') }}" class="block rounded-md px-3 py-2 hover:bg-slate-800 {{ request()->routeIs('admin.users.*') ? 'bg-slate-800' : '' }}">Team Members</a>
                    @endif
                </nav>
                <div class="px-6 py-4 text-xs text-slate-400">Logged in as {{ auth()->user()->name }}</div>
            </aside>
            <div class="flex-1 flex flex-col">
                <header class="bg-white shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                        <div class="text-lg font-semibold">@yield('header', 'Admin')</div>
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900">Storefront</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="rounded-md bg-slate-900 px-3 py-1.5 text-white text-xs uppercase tracking-wide">Logout</button>
                            </form>
                        </div>
                    </div>
                </header>
                <main class="flex-1 bg-slate-100">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        @include('partials.flash')
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
