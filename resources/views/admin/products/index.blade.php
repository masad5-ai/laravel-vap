@extends('layouts.admin')

@section('header', 'Products')

@section('content')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Catalogue</h1>
            <p class="text-sm text-slate-500">Manage inventory, pricing and merchandising.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">New product</a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Product</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900">{{ $product->name }}</div>
                            <div class="text-xs text-slate-500">SKU {{ $product->sku }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $product->category?->name }}</td>
                        <td class="px-4 py-3 text-slate-600">${{ number_format($product->price, 2) }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $product->stock }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">{{ $product->is_active ? 'Active' : 'Draft' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
