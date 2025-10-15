@csrf
<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Basics</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <label class="text-sm text-slate-600">Name
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">SKU
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">Slug
                    <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">Brand
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">Category
                    <select name="category_id" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm text-slate-600">Nicotine strength
                    <input type="text" name="nicotine_strength" value="{{ old('nicotine_strength', $product->nicotine_strength) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">Flavour profile
                    <input type="text" name="flavour_profile" value="{{ old('flavour_profile', $product->flavour_profile) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
            </div>
            <label class="mt-4 block text-sm text-slate-600">Short description
                <textarea name="short_description" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2">{{ old('short_description', $product->short_description) }}</textarea>
            </label>
            <label class="mt-4 block text-sm text-slate-600">Description
                <textarea name="description" rows="6" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2">{{ old('description', $product->description) }}</textarea>
            </label>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Media</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <label class="text-sm text-slate-600">Hero image URL
                    <input type="url" name="hero_image" value="{{ old('hero_image', $product->hero_image) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">Thumbnail URL
                    <input type="url" name="thumbnail" value="{{ old('thumbnail', $product->thumbnail) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
            </div>
            <div class="mt-4 space-y-3">
                <p class="text-xs text-slate-500">Additional gallery images</p>
                @for($i = 0; $i < 4; $i++)
                    <div class="grid gap-3 sm:grid-cols-[1fr_auto]">
                        <input type="url" name="images[{{ $i }}][url]" value="{{ old("images.$i.url", $product->images[$i]->path ?? '') }}" placeholder="https://" class="rounded-lg border border-slate-200 px-3 py-2" />
                        <input type="text" name="images[{{ $i }}][alt]" value="{{ old("images.$i.alt", $product->images[$i]->alt_text ?? '') }}" placeholder="Alt text" class="rounded-lg border border-slate-200 px-3 py-2" />
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Pricing & inventory</h2>
            <div class="mt-4 space-y-4">
                <label class="text-sm text-slate-600">Price (AUD)
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">Compare at price
                    <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">Stock
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <label class="text-sm text-slate-600">Safety stock
                    <input type="number" name="safety_stock" value="{{ old('safety_stock', $product->safety_stock) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
                </label>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-slate-300" />
                    <span>Visible on storefront</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-slate-300" />
                    <span>Mark as featured</span>
                </div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Meta</h2>
            <label class="block text-sm text-slate-600">Attributes (JSON)
                <textarea name="attributes[custom]" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" placeholder='Key details e.g. "pg_vg_ratio"'>{{ old('attributes.custom') }}</textarea>
            </label>
            <label class="mt-4 block text-sm text-slate-600">Metadata (JSON)
                <textarea name="metadata[seo_title]" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" placeholder="SEO title override">{{ old('metadata.seo_title', data_get($product->metadata, 'seo_title')) }}</textarea>
            </label>
        </div>
        <button class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white">Save product</button>
    </div>
</div>
