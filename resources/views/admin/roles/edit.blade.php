@extends('layouts.admin')

@section('title', 'Edit Role | '.config('app.name'))
@section('header', 'Edit Role')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
            @method('PUT')
            @include('admin.roles.partials.form')
        </form>
    </div>
@endsection
