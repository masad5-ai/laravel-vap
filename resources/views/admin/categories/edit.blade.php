@extends('layouts.admin')

@section('header', 'Edit category')

@section('content')
    <form method="post" action="{{ route('admin.categories.update', $category) }}" class="max-w-3xl space-y-4">
        @csrf
        @method('put')
        @include('admin.categories.partials.form')
        <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Update category</button>
    </form>
    <form method="post" action="{{ route('admin.categories.destroy', $category) }}" class="mt-4">
        @csrf
        @method('delete')
        <button class="text-sm text-rose-600">Delete category</button>
    </form>
@endsection
