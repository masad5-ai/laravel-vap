@extends('layouts.admin')

@section('title', 'Team Members | '.config('app.name'))
@section('header', 'Team Members')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Team Members</h1>
            <p class="text-sm text-slate-500">Assign store roles and direct permissions to internal users.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Roles</th>
                    <th class="px-6 py-3 text-left">Permissions</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            <div class="flex flex-wrap gap-2">
                                @forelse($user->roles as $role)
                                    <span class="inline-flex items-center rounded-full bg-slate-200 px-2.5 py-0.5 text-xs text-slate-700">{{ $role->name }}</span>
                                @empty
                                    <span class="text-xs text-slate-400">No role</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <div class="flex flex-wrap gap-2">
                                @forelse($user->permissions as $permission)
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs text-amber-700">{{ $permission->name }}</span>
                                @empty
                                    <span class="text-xs text-slate-400">Inherited</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-slate-700 hover:text-slate-900">Manage</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
