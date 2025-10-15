@extends('layouts.admin')

@section('header', 'Coupons')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Promotions</h1>
            <p class="text-sm text-slate-500">Manage incentives and customer loyalty.</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">New coupon</a>
    </div>
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Discount</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Expiry</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($coupons as $coupon)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-slate-900">{{ $coupon->code }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $coupon->name }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value.'%' : '$'.number_format($coupon->discount_value, 2) }}
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $coupon->is_active ? 'Active' : 'Disabled' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ optional($coupon->expires_at)->toFormattedDateString() ?? '—' }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-indigo-600">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $coupons->links() }}</div>
@endsection
