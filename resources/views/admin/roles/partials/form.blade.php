@csrf
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-slate-700">Display Name</label>
            <input type="text" name="name" value="{{ old('name', $role->name) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $role->slug) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="auto-generated if blank">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Description</label>
            <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Optional summary">{{ old('description', $role->description) }}</textarea>
        </div>
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_default" value="1" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900" {{ old('is_default', $role->is_default) ? 'checked' : '' }}>
            <span>Assign automatically to newly registered customers</span>
        </label>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Permissions</label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-slate-50 border border-slate-200 rounded-lg p-4 max-h-80 overflow-y-auto">
            @foreach($permissions as $permission)
                <label class="flex items-start gap-3 text-sm text-slate-700">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="mt-1 rounded border-slate-300 text-slate-900 focus:ring-slate-900"
                        {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <span>
                        <span class="font-medium">{{ $permission->name }}</span>
                        <span class="block text-xs text-slate-500">{{ $permission->description }}</span>
                    </span>
                </label>
            @endforeach
        </div>
    </div>
</div>
<div class="mt-6 flex items-center gap-3">
    <button class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-white text-sm font-medium">Save Role</button>
    <a href="{{ route('admin.roles.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Cancel</a>
</div>
