<div class="rounded-2xl bg-white p-6 shadow-sm space-y-4">
    <label class="block text-sm text-slate-600">Name
        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
    </label>
    <label class="block text-sm text-slate-600">Slug
        <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
    </label>
    <label class="block text-sm text-slate-600">Parent category
        <select name="parent_id" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2">
            <option value="">Root</option>
            @foreach($parents as $parent)
                <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="block text-sm text-slate-600">Hero image URL
        <input type="url" name="hero_image" value="{{ old('hero_image', $category->hero_image) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
    </label>
    <label class="block text-sm text-slate-600">Description
        <textarea name="description" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2">{{ old('description', $category->description) }}</textarea>
    </label>
    <div class="flex items-center gap-2 text-sm text-slate-600">
        <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $category->is_visible) ? 'checked' : '' }} class="rounded border-slate-300" />
        <span>Show in storefront navigation</span>
    </div>
    <label class="block text-sm text-slate-600">Meta title
        <input type="text" name="meta_title" value="{{ old('meta_title', $category->meta_title) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
    </label>
    <label class="block text-sm text-slate-600">Meta description
        <textarea name="meta_description" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2">{{ old('meta_description', $category->meta_description) }}</textarea>
    </label>
</div>
