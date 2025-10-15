@extends('layouts.admin')

@section('title', 'Edit Integration | '.config('app.name'))
@section('header', 'Edit Gateway Integration')

@section('content')
    <div class="max-w-5xl">
        <div class="mb-6">
            <p class="text-sm text-slate-600">Update provider credentials or adjust custom endpoint details without redeploying code.</p>
        </div>
        <form method="POST" action="{{ route('admin.integrations.update', $integration) }}" class="bg-white p-6 rounded-lg shadow">
            @method('PUT')
            @include('admin.integrations.partials.form')
        </form>
    </div>
@endsection
