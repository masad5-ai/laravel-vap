@extends('layouts.admin')

@section('header', 'Edit coupon')

@section('content')
    <form method="post" action="{{ route('admin.coupons.update', $coupon) }}" class="max-w-3xl space-y-4">
        @csrf
        @method('put')
        @include('admin.coupons.partials.form')
        <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Update coupon</button>
    </form>
    <form method="post" action="{{ route('admin.coupons.destroy', $coupon) }}" class="mt-4">
        @csrf
        @method('delete')
        <button class="text-sm text-rose-600">Delete coupon</button>
    </form>
@endsection
