<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'permission:manage-roles']);
    }

    public function index(): View
    {
        $permissions = Permission::withCount(['roles', 'users'])->orderBy('name')->paginate(15);

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create(): View
    {
        return view('admin.permissions.create', ['permission' => new Permission()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $permission = Permission::create($this->validatePermission($request));

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $permission->update($this->validatePermission($request, $permission));

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        if ($permission->roles()->exists() || $permission->users()->exists()) {
            return back()->with('error', 'Permissions in use cannot be deleted.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatePermission(Request $request, ?Permission $permission = null): array
    {
        $permissionId = $permission?->getKey();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('permissions', 'slug')->ignore($permissionId)],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['slug'] = Str::slug($validated['slug'] ?? $validated['name']);

        return $validated;
    }
}
