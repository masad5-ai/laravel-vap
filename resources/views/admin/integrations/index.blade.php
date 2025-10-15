@extends('layouts.admin')

@section('title', 'Gateways | '.config('app.name'))
@section('header', 'Gateway Integrations')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Gateway Integrations</h1>
            <p class="text-sm text-slate-500">Centralise payment, email, SMS, and WhatsApp credentials.</p>
        </div>
        <a href="{{ route('admin.integrations.create') }}" class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-white text-sm font-medium shadow">
            <span>Add Integration</span>
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-left">Provider</th>
                    <th class="px-6 py-3 text-left">Active</th>
                    <th class="px-6 py-3 text-left">Last Updated</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($integrations as $integration)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $integration->name }}</td>
                        <td class="px-6 py-4 text-slate-600 capitalize">{{ str_replace('_', ' ', $integration->type) }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $integration->provider ?? 'Custom' }}</td>
                        <td class="px-6 py-4">
                            @if($integration->is_active)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Active</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-200 px-2.5 py-0.5 text-xs font-medium text-slate-600">Disabled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $integration->updated_at?->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex gap-3">
                                <a href="{{ route('admin.integrations.edit', $integration) }}" class="text-slate-700 hover:text-slate-900">Edit</a>
                                <form action="{{ route('admin.integrations.destroy', $integration) }}" method="POST" onsubmit="return confirm('Remove this integration?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-600 hover:text-rose-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">No integrations configured yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $integrations->links() }}
        </div>
    </div>
@endsection
