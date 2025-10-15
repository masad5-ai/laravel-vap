<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'permission:manage-roles']);
    }

    public function index(): View
    {
        $roles = Role::withCount(['permissions', 'users'])->orderBy('name')->paginate(12);

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'role' => new Role(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRole($request);

        $role = Role::create($validated);
        $role->permissions()->sync($request->collect('permissions')->filter()->all());

        $this->enforceSingleDefault($role);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.edit', [
            'role' => $role->load('permissions'),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $this->validateRole($request, $role);

        $role->update($validated);
        $role->permissions()->sync($request->collect('permissions')->filter()->all());

        $this->enforceSingleDefault($role);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_default) {
            return back()->with('error', 'Default roles cannot be deleted.');
        }

        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateRole(Request $request, ?Role $role = null): array
    {
        $roleId = $role?->getKey();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('roles', 'slug')->ignore($roleId)],
            'description' => ['nullable', 'string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['slug'] ?? $validated['name']);
        $validated['is_default'] = (bool) ($validated['is_default'] ?? false);

        return $validated;
    }

    protected function enforceSingleDefault(Role $role): void
    {
        if ($role->is_default) {
            Role::where('id', '!=', $role->getKey())->update(['is_default' => false]);
        }
    }
}
