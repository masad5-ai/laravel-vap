@extends('layouts.admin')

@section('title', 'Roles | '.config('app.name'))
@section('header', 'Roles & Permissions')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Roles</h1>
            <p class="text-sm text-slate-500">Assign permissions and organise platform responsibilities.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-white text-sm font-medium shadow">
            <span>New Role</span>
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Slug</th>
                    <th class="px-6 py-3 text-left">Permissions</th>
                    <th class="px-6 py-3 text-left">Members</th>
                    <th class="px-6 py-3 text-left">Default</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($roles as $role)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">
                            <div>{{ $role->name }}</div>
                            <div class="text-xs text-slate-500">{{ $role->description }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $role->slug }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $role->permissions_count }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $role->users_count }}</td>
                        <td class="px-6 py-4">
                            @if($role->is_default)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700">Default</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex gap-3">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-slate-700 hover:text-slate-900">Edit</a>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-600 hover:text-rose-700" @if($role->is_default) disabled @endif>Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">No roles defined yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $roles->links() }}
        </div>
    </div>
@endsection
