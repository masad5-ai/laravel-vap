@csrf
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-slate-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $integration->name) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Type</label>
            <select name="type" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required>
                <option value="">Select type</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(old('type', $integration->type) === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Provider</label>
            <input type="text" name="provider" value="{{ old('provider', $integration->provider) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="e.g. Stripe, Twilio">
        </div>
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900" {{ old('is_active', $integration->is_active ?? true) ? 'checked' : '' }}>
            <span>Integration is active</span>
        </label>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">Credentials & Settings</label>
        <p class="text-xs text-slate-500 mb-3">Use key/value pairs to capture secrets, tokens, sender IDs, or configuration toggles. Empty fields are ignored.</p>
        <div class="space-y-3">
            @php
                $settings = collect(old('settings', collect($integration->settings ?? [])->map(fn ($value, $key) => ['key' => $key, 'value' => $value])->values()->toArray()));
                if ($settings->isEmpty()) {
                    $settings = collect([
                        ['key' => '', 'value' => ''],
                        ['key' => '', 'value' => ''],
                        ['key' => '', 'value' => ''],
                    ]);
                }
            @endphp
            @foreach($settings as $index => $pair)
                @php
                    $settingKey = is_array($pair) ? ($pair['key'] ?? '') : (is_string($index) ? $index : '');
                    $settingValue = is_array($pair) ? ($pair['value'] ?? '') : ($pair ?? '');
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <input type="text" name="settings[{{ $index }}][key]" value="{{ $settingKey }}" class="md:col-span-2 rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Key (e.g. api_key)">
                    <input type="text" name="settings[{{ $index }}][value]" value="{{ $settingValue }}" class="md:col-span-3 rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Value">
                </div>
            @endforeach
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="settings[{{ $settings->count() }}][key]" class="md:col-span-2 rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Key (optional)">
                <input type="text" name="settings[{{ $settings->count() }}][value]" class="md:col-span-3 rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Value (optional)">
            </div>
        </div>
    </div>
</div>
<div class="mt-6 flex items-center gap-3">
    <button class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-white text-sm font-medium">Save Integration</button>
    <a href="{{ route('admin.integrations.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Cancel</a>
</div>
