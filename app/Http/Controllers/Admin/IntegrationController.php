<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'permission:manage-integrations']);
    }

    public function index(): View
    {
        $integrations = Integration::orderBy('type')->orderBy('name')->paginate(15);

        return view('admin.integrations.index', compact('integrations'));
    }

    public function create(): View
    {
        return view('admin.integrations.create', [
            'integration' => new Integration(['is_active' => true, 'driver' => Integration::DRIVER_BUILTIN]),
            'types' => Integration::types(),
            'drivers' => Integration::drivers(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $integration = Integration::create($this->validatedIntegration($request));

        return redirect()->route('admin.integrations.index')->with('success', 'Integration saved successfully.');
    }

    public function edit(Integration $integration): View
    {
        return view('admin.integrations.edit', [
            'integration' => $integration,
            'types' => Integration::types(),
            'drivers' => Integration::drivers(),
        ]);
    }

    public function update(Request $request, Integration $integration): RedirectResponse
    {
        $integration->update($this->validatedIntegration($request, $integration));

        return redirect()->route('admin.integrations.index')->with('success', 'Integration updated successfully.');
    }

    public function destroy(Integration $integration): RedirectResponse
    {
        $integration->delete();

        return redirect()->route('admin.integrations.index')->with('success', 'Integration deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedIntegration(Request $request, ?Integration $integration = null): array
    {
        $integrationId = $integration?->getKey();

        $settings = collect($request->input('settings', []))
            ->mapWithKeys(function (array $pair, int $index) {
                $key = trim((string) ($pair['key'] ?? ''));
                $value = trim((string) ($pair['value'] ?? ''));

                if ($key === '' || $value === '') {
                    return [];
                }

                return [$key => $value];
            })
            ->all();

        $headers = collect($request->input('endpoint_headers', []))
            ->mapWithKeys(function (array $pair) {
                $key = trim((string) ($pair['key'] ?? ''));
                $value = trim((string) ($pair['value'] ?? ''));

                if ($key === '' || $value === '') {
                    return [];
                }

                return [$key => $value];
            })
            ->all();

        $payloadFormat = $request->input('endpoint_payload_format');

        if ($payloadFormat) {
            $settings['payload_format'] = $payloadFormat;
        }

        $payload = $request->all();
        $payload['settings'] = $settings;
        $payload['endpoint_headers'] = $headers;

        if (! empty($payload['endpoint_method'])) {
            $payload['endpoint_method'] = strtoupper($payload['endpoint_method']);
        }

        $validator = validator($payload, [
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'string', Rule::in(Integration::types())],
            'driver' => ['required', 'string', Rule::in(Integration::drivers())],
            'provider' => ['nullable', 'string', 'max:150'],
            'is_active' => ['sometimes', 'boolean'],
            'settings' => ['nullable', 'array'],
            'settings.*' => ['nullable', 'string', 'max:255'],
            'endpoint_url' => ['nullable', 'url'],
            'endpoint_method' => ['nullable', 'string', Rule::in(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])],
            'endpoint_headers' => ['nullable', 'array'],
            'endpoint_headers.*' => ['nullable', 'string', 'max:255'],
            'endpoint_payload_template' => ['nullable', 'string'],
            'endpoint_payload_format' => ['nullable', 'string', Rule::in(['json', 'form'])],
        ]);

        $validated = $validator->validate();

        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);
        $validated['provider'] = $validated['provider'] ?? null;
        $validated['endpoint_method'] = $validated['endpoint_method'] ?? null;

        $type = $validated['type'];

        validator(
            ['name' => $validated['name']],
            [
                'name' => [
                    Rule::unique('integrations')->where(fn ($query) => $query->where('type', $type))->ignore($integrationId),
                ],
            ]
        )->validate();

        if ($validated['driver'] === Integration::DRIVER_CUSTOM_HTTP) {
            validator($validated, [
                'endpoint_url' => ['required', 'url'],
                'endpoint_method' => ['required', Rule::in(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])],
            ])->validate();
        }

        return Arr::only($validated, [
            'name',
            'type',
            'driver',
            'provider',
            'settings',
            'is_active',
            'endpoint_url',
            'endpoint_method',
            'endpoint_headers',
            'endpoint_payload_template',
        ]);
    }
}
