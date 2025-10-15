@extends('layouts.admin')

@section('title', 'Permissions | '.config('app.name'))
@section('header', 'Permissions')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Permissions</h1>
            <p class="text-sm text-slate-500">Define granular capabilities to compose new roles.</p>
        </div>
        <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-white text-sm font-medium shadow">
            <span>New Permission</span>
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Slug</th>
                    <th class="px-6 py-3 text-left">Description</th>
                    <th class="px-6 py-3 text-left">Roles</th>
                    <th class="px-6 py-3 text-left">Direct Users</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($permissions as $permission)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $permission->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $permission->slug }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $permission->description }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $permission->roles_count }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $permission->users_count }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex gap-3">
                                <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-slate-700 hover:text-slate-900">Edit</a>
                                <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Delete this permission?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-rose-600 hover:text-rose-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">No permissions defined yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $permissions->links() }}
        </div>
    </div>
@endsection
