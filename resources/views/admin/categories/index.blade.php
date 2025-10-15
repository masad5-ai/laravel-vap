@extends('layouts.admin')

@section('header', 'Categories')

@section('content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Collections</h1>
            <p class="text-sm text-slate-500">Structure the storefront navigation.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">New category</a>
    </div>
    <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Parent</th>
                    <th class="px-4 py-3">Visible</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($categories as $category)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-900">{{ $category->name }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $category->slug }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $category->parent?->name ?? 'Root' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $category->is_visible ? 'Yes' : 'Hidden' }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
