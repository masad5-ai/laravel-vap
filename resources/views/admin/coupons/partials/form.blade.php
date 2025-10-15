<div class="rounded-2xl bg-white p-6 shadow-sm space-y-4">
    <label class="block text-sm text-slate-600">Code
        <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
    </label>
    <label class="block text-sm text-slate-600">Name
        <input type="text" name="name" value="{{ old('name', $coupon->name) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
    </label>
    <label class="block text-sm text-slate-600">Description
        <textarea name="description" rows="3" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2">{{ old('description', $coupon->description) }}</textarea>
    </label>
    <div class="grid gap-4 sm:grid-cols-2">
        <label class="text-sm text-slate-600">Discount type
            <select name="discount_type" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2">
                <option value="fixed" @selected(old('discount_type', $coupon->discount_type) === 'fixed')>Fixed amount</option>
                <option value="percentage" @selected(old('discount_type', $coupon->discount_type) === 'percentage')>Percentage</option>
            </select>
        </label>
        <label class="text-sm text-slate-600">Discount value
            <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
        </label>
        <label class="text-sm text-slate-600">Max discount
            <input type="number" step="0.01" name="max_discount_value" value="{{ old('max_discount_value', $coupon->max_discount_value) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
        </label>
        <label class="text-sm text-slate-600">Min cart total
            <input type="number" step="0.01" name="minimum_cart_total" value="{{ old('minimum_cart_total', $coupon->minimum_cart_total) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
        </label>
        <label class="text-sm text-slate-600">Max redemptions
            <input type="number" name="max_redemptions" value="{{ old('max_redemptions', $coupon->max_redemptions) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
        </label>
        <label class="text-sm text-slate-600">Starts at
            <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d\TH:i')) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
        </label>
        <label class="text-sm text-slate-600">Expires at
            <input type="datetime-local" name="expires_at" value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d\TH:i')) }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2" />
        </label>
    </div>
    <div class="flex items-center gap-4 text-sm text-slate-600">
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }} class="rounded border-slate-300" /> Active</label>
        <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_stackable" value="1" {{ old('is_stackable', $coupon->is_stackable) ? 'checked' : '' }} class="rounded border-slate-300" /> Stackable</label>
    </div>
</div>
