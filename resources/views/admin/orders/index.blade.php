@extends('layouts.admin')

@section('header', 'Orders')

@section('content')
    <form method="get" class="flex flex-wrap items-end gap-3">
        <label class="text-sm text-slate-600">
            Status
            <select name="status" class="mt-1 rounded-md border border-slate-300 px-3 py-2 text-sm">
                <option value="">All</option>
                @foreach(['pending','processing','shipped','completed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </label>
        <label class="text-sm text-slate-600">
            Search
            <input type="search" name="search" value="{{ request('search') }}" class="mt-1 rounded-md border border-slate-300 px-3 py-2 text-sm" placeholder="Order # or email" />
        </label>
        <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Filter</button>
    </form>

    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Placed</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $order->customer_name }}<br><span class="text-xs text-slate-400">{{ $order->email }}</span></td>
                        <td class="px-4 py-3 text-slate-600">{{ ucfirst($order->status) }}<br><span class="text-xs text-slate-400">{{ ucfirst($order->payment_status) }}</span></td>
                        <td class="px-4 py-3 text-slate-600">${{ number_format($order->grand_total, 2) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600">Manage</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
@endsection
