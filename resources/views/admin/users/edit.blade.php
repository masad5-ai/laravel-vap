@extends('layouts.admin')

@section('title', 'Manage User Access | '.config('app.name'))
@section('header', 'Manage User Access')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900">{{ $user->name }}</h2>
            <p class="text-sm text-slate-500">{{ $user->email }}</p>
        </div>
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-slate-700 mb-3">Roles</h3>
                    <div class="space-y-2">
                        @foreach($roles as $role)
                            <label class="flex items-start gap-3 text-sm text-slate-700">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mt-1 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                                    {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span>
                                    <span class="font-medium">{{ $role->name }}</span>
                                    <span class="block text-xs text-slate-500">{{ $role->description }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-slate-700 mb-3">Direct Permissions</h3>
                    <p class="text-xs text-slate-500 mb-3">Assign specific capabilities beyond role inheritance. Super administrators receive all permissions automatically.</p>
                    <div class="space-y-2 max-h-72 overflow-y-auto border border-slate-200 rounded-lg p-4 bg-slate-50">
                        @foreach($permissions as $permission)
                            <label class="flex items-start gap-3 text-sm text-slate-700">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="mt-1 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                                    {{ in_array($permission->id, old('permissions', $user->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <span>
                                    <span class="font-medium">{{ $permission->name }}</span>
                                    <span class="block text-xs text-slate-500">{{ $permission->description }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-white text-sm font-medium">Save Access</button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Back to team</a>
            </div>
        </form>
    </div>
@endsection
