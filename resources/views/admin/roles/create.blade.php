@extends('layouts.admin')

@section('title', 'Create Role | '.config('app.name'))
@section('header', 'Create Role')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
            @include('admin.roles.partials.form')
        </form>
    </div>
@endsection
