@extends('layouts.admin')

@section('header', 'Create category')

@section('content')
    <form method="post" action="{{ route('admin.categories.store') }}" class="max-w-3xl space-y-4">
        @csrf
        @include('admin.categories.partials.form')
        <button class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Save category</button>
    </form>
@endsection
