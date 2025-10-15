@extends('layouts.admin')

@section('title', 'Edit Permission | '.config('app.name'))
@section('header', 'Edit Permission')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.permissions.partials.form')
        </form>
    </div>
@endsection
