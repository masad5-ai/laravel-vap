<div class="space-y-3">
    <div>
        <x-input-label value="Name" />
        <x-text-input name="name" class="w-full" value="{{ old('name', $product->name ?? '') }}" required />
        <x-input-error :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label value="Description" />
        <textarea name="description" class="w-full border-gray-300 rounded" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <x-input-label value="Price" />
            <x-text-input name="price" type="number" step="0.01" value="{{ old('price', $product->price ?? '') }}" required />
            <x-input-error :messages="$errors->get('price')" />
        </div>
        <div>
            <x-input-label value="Stock" />
            <x-text-input name="stock" type="number" value="{{ old('stock', $product->stock ?? '') }}" required />
            <x-input-error :messages="$errors->get('stock')" />
        </div>
        <div>
            <x-input-label value="Image URL" />
            <x-text-input name="image_url" class="w-full" value="{{ old('image_url', $product->image_url ?? '') }}" />
            <x-input-error :messages="$errors->get('image_url')" />
        </div>
    </div>
</div>
