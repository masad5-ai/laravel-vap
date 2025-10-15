@extends('layouts.admin')

@section('title', 'Add Integration | '.config('app.name'))
@section('header', 'Add Gateway Integration')

@section('content')
    <div class="max-w-5xl">
        <div class="mb-6">
            <p class="text-sm text-slate-600">Connect payment, email, SMS, or WhatsApp providers – including custom scripts hosted on shared servers.</p>
        </div>
        <form method="POST" action="{{ route('admin.integrations.store') }}" class="bg-white p-6 rounded-lg shadow">
            @include('admin.integrations.partials.form')
        </form>
    </div>
@endsection
