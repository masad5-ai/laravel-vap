@extends('layouts.admin')

@section('header', 'Create product')

@section('content')
    <form method="post" action="{{ route('admin.products.store') }}" class="space-y-6">
        @include('admin.products._form')
    </form>
@endsection
