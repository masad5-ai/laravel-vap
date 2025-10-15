@extends('layouts.admin')

@section('header', 'Order '.$order->order_number)

@section('content')
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Items</h2>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    @foreach($order->items as $item)
                        <div class="flex justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $item->product_name }}</p>
                                <p class="text-xs text-slate-400">SKU {{ $item->sku }}</p>
                            </div>
                            <div class="text-right">
                                <p>{{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                                <p class="font-semibold text-slate-900">${{ number_format($item->line_total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Customer</h2>
                <p class="mt-2 text-sm text-slate-600">{{ $order->customer_name }}<br>{{ $order->email }}<br>{{ $order->phone }}</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Billing</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ implode(', ', array_filter($order->billing_address ?? [])) }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Shipping</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ implode(', ', array_filter($order->shipping_address ?? [])) }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <form method="post" action="{{ route('admin.orders.update', $order) }}" class="rounded-2xl bg-white p-6 shadow-sm space-y-4">
                @csrf
                @method('put')
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Status</h2>
                <label class="text-sm text-slate-600">Fulfilment status
                    <select name="status" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        @foreach(['pending','processing','shipped','completed','cancelled'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm text-slate-600">Payment status
                    <select name="payment_status" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        @foreach(['unpaid','paid','refunded','failed'] as $status)
                            <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm text-slate-600">Tracking number
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm" />
                </label>
                <label class="text-sm text-slate-600">Notes
                    <textarea name="notes" rows="3" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('notes', $order->notes) }}</textarea>
                </label>
                <button class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Update order</button>
            </form>
            <div class="rounded-2xl bg-white p-6 shadow-sm text-sm text-slate-600">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Financials</h2>
                <dl class="mt-3 space-y-2">
                    <div class="flex justify-between"><dt>Subtotal</dt><dd>${{ number_format($order->subtotal, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Discount</dt><dd>- ${{ number_format($order->discount_total, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Tax</dt><dd>${{ number_format($order->tax_total, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Shipping</dt><dd>${{ number_format($order->shipping_total, 2) }}</dd></div>
                    <div class="flex justify-between font-semibold text-slate-900"><dt>Total</dt><dd>${{ number_format($order->grand_total, 2) }}</dd></div>
                </dl>
            </div>
        </div>
    </div>
@endsection
