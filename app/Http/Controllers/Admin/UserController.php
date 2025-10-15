<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin', 'permission:manage-roles']);
    }

    public function index(Request $request): View
    {
        $users = User::query()
            ->with(['roles', 'permissions'])
            ->orderBy('name')
            ->paginate(perPage: 15)
            ->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load(['roles', 'permissions']),
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'roles' => ['array'],
            'roles.*' => ['integer', 'exists:roles,id'],
            'permissions' => ['array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $user->roles()->sync($request->collect('roles')->filter()->all());
        $user->permissions()->sync($request->collect('permissions')->filter()->all());

        return redirect()->route('admin.users.edit', $user)->with('success', 'User access updated successfully.');
    }
}
