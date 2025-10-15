@extends('layouts.admin')

@section('title', 'Create Permission | '.config('app.name'))
@section('header', 'Create Permission')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-6">
            @csrf
            @include('admin.permissions.partials.form')
        </form>
    </div>
@endsection
