<div class="space-y-5">
    <div>
        <label class="block text-sm font-medium text-slate-700">Display Name</label>
        <input type="text" name="name" value="{{ old('name', $permission->name) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $permission->slug) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="auto-generated if blank">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Optional summary">{{ old('description', $permission->description) }}</textarea>
    </div>
</div>
<div class="flex items-center gap-3 pt-4">
    <button class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-white text-sm font-medium">Save Permission</button>
    <a href="{{ route('admin.permissions.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Cancel</a>
</div>
