@extends('layouts.admin')

@section('header', 'Commerce performance')

@section('content')
    <div class="grid gap-6 lg:grid-cols-5">
        <div class="rounded-2xl bg-white p-6 shadow-sm lg:col-span-3">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Revenue this year</h2>
            <div class="mt-4 flex flex-wrap gap-4 text-3xl font-semibold text-slate-900">
                ${{ number_format($metrics['revenue'], 2) }}
            </div>
            <div class="mt-6 grid grid-cols-3 gap-4 text-sm text-slate-500">
                <div>
                    <p class="text-xs uppercase tracking-wide">Orders</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ $metrics['orders'] }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide">Customers</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ $metrics['customers'] }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wide">Active SKUs</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ $metrics['products'] }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Inventory alerts</h2>
            <p class="mt-4 text-3xl font-semibold text-amber-600">{{ $metrics['lowStock'] }}</p>
            <p class="mt-2 text-xs text-slate-500">Products at or below safety stock thresholds.</p>
            <a href="{{ route('admin.products.index') }}" class="mt-4 inline-flex items-center text-sm text-indigo-600">Review catalogue →</a>
        </div>
    </div>

    <div class="mt-10 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Recent orders</h2>
        <div class="mt-4 divide-y divide-slate-100 text-sm text-slate-600">
            @foreach($recentOrders as $order)
                <div class="flex flex-wrap items-center justify-between gap-4 py-3">
                    <div>
                        <p class="font-semibold text-slate-900">{{ $order->order_number }}</p>
                        <p class="text-xs text-slate-400">{{ $order->customer_name }} · {{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">{{ $order->status }}</span>
                        <span class="text-base font-semibold text-slate-900">${{ number_format($order->grand_total, 2) }}</span>
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600">Manage</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
