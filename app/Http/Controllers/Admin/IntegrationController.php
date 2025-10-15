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
            'integration' => new Integration(['is_active' => true]),
            'types' => Integration::types(),
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

        $payload = $request->all();
        $payload['settings'] = $settings;

        $validator = validator($payload, [
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'string', Rule::in(Integration::types())],
            'provider' => ['nullable', 'string', 'max:150'],
            'is_active' => ['sometimes', 'boolean'],
            'settings' => ['nullable', 'array'],
            'settings.*' => ['nullable', 'string', 'max:255'],
        ]);

        $validated = $validator->validate();

        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);
        $validated['provider'] = $validated['provider'] ?? null;

        $type = $validated['type'];

        validator(
            ['name' => $validated['name']],
            [
                'name' => [
                    Rule::unique('integrations')->where(fn ($query) => $query->where('type', $type))->ignore($integrationId),
                ],
            ]
        )->validate();

        return Arr::only($validated, ['name', 'type', 'provider', 'settings', 'is_active']);
    }
}
