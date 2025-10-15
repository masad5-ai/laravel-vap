@csrf
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6" data-integration-form>
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
            <label class="block text-sm font-medium text-slate-700">Driver</label>
            <select name="driver" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" required data-driver-select>
                @foreach($drivers as $driverOption)
                    <option value="{{ $driverOption }}" @selected(old('driver', $integration->driver ?? \App\Models\Integration::DRIVER_BUILTIN) === $driverOption)>{{ ucwords(str_replace('-', ' ', $driverOption)) }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500" data-driver-helper="{{ \App\Models\Integration::DRIVER_CUSTOM_HTTP }}">Calls an external HTTP endpoint, perfect for bespoke scripts hosted anywhere.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">Provider</label>
            <input type="text" name="provider" value="{{ old('provider', $integration->provider) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="e.g. Stripe, Twilio">
        </div>
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900" {{ old('is_active', $integration->is_active ?? true) ? 'checked' : '' }}>
            <span>Integration is active</span>
        </label>
        <div class="space-y-4 rounded-lg border border-slate-200 bg-slate-50 p-4" data-driver-panel="{{ \App\Models\Integration::DRIVER_CUSTOM_HTTP }}">
            <h3 class="text-sm font-semibold text-slate-700">Custom Script Endpoint</h3>
            <div>
                <label class="block text-xs font-medium uppercase tracking-wide text-slate-600">Endpoint URL</label>
                <input type="url" name="endpoint_url" value="{{ old('endpoint_url', $integration->endpoint_url) }}" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="https://your-script.example.com/process.php">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-600">HTTP Method</label>
                    <select name="endpoint_method" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        @php($methods = ['POST', 'GET', 'PUT', 'PATCH', 'DELETE'])
                        <option value="">Select method</option>
                        @foreach($methods as $method)
                            <option value="{{ $method }}" @selected(old('endpoint_method', $integration->endpoint_method) === $method)>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium uppercase tracking-wide text-slate-600">Payload Template</label>
                    @php($payloadFormat = old('endpoint_payload_format', data_get($integration->settings, 'payload_format', 'json')))
                    <select name="endpoint_payload_format" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                        <option value="json" @selected($payloadFormat === 'json')>JSON</option>
                        <option value="form" @selected($payloadFormat === 'form')>Form encoded</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium uppercase tracking-wide text-slate-600">Payload Body</label>
                <textarea name="endpoint_payload_template" rows="6" class="mt-1 w-full rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder='{"amount":"{{ amount }}","order":"{{ order_reference }}"}'>{{ old('endpoint_payload_template', $integration->endpoint_payload_template) }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Use <code class="font-mono">{{ '{{ placeholder }}' }}</code> tokens to map checkout data into your script.</p>
            </div>
            <div>
                <label class="block text-xs font-medium uppercase tracking-wide text-slate-600 mb-2">Headers (optional)</label>
                @php
                    $headerValues = collect(old('endpoint_headers', collect($integration->endpoint_headers ?? [])->map(fn ($value, $key) => ['key' => $key, 'value' => $value])->values()->toArray()));
                    if ($headerValues->isEmpty()) {
                        $headerValues = collect([
                            ['key' => '', 'value' => ''],
                            ['key' => '', 'value' => ''],
                        ]);
                    }
                @endphp
                <div class="space-y-3">
                    @foreach($headerValues as $index => $pair)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" name="endpoint_headers[{{ $index }}][key]" value="{{ $pair['key'] ?? '' }}" class="rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Header (e.g. X-Api-Key)">
                            <input type="text" name="endpoint_headers[{{ $index }}][value]" value="{{ $pair['value'] ?? '' }}" class="rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Value">
                        </div>
                    @endforeach
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="text" name="endpoint_headers[{{ $headerValues->count() }}][key]" class="rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Header (optional)">
                        <input type="text" name="endpoint_headers[{{ $headerValues->count() }}][value]" class="rounded-md border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500" placeholder="Value (optional)">
                    </div>
                </div>
            </div>
        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.querySelector('[data-integration-form]');
        if (! wrapper) {
            return;
        }

        const select = wrapper.querySelector('[data-driver-select]');
        const panels = wrapper.querySelectorAll('[data-driver-panel]');
        const helpers = wrapper.querySelectorAll('[data-driver-helper]');

        const togglePanels = () => {
            const driver = select.value;
            panels.forEach((panel) => {
                const shouldShow = panel.dataset.driverPanel === driver;
                panel.style.display = shouldShow ? '' : 'none';
            });
            helpers.forEach((helper) => {
                helper.style.display = helper.dataset.driverHelper === driver ? '' : 'none';
            });
        };

        select.addEventListener('change', togglePanels);
        togglePanels();
    });
</script>
