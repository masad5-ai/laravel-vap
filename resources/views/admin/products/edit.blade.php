@extends('layouts.admin')

@section('header', 'Edit product')

@section('content')
    <form method="post" action="{{ route('admin.products.update', $product) }}" class="space-y-6">
        @method('put')
        @include('admin.products._form')
    </form>
    <form method="post" action="{{ route('admin.products.destroy', $product) }}" class="mt-6">
        @csrf
        @method('delete')
        <button class="text-sm text-rose-600">Archive product</button>
    </form>
@endsection
