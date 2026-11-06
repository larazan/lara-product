<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    //
    public function index(Request $request)
    {
        // 🔎 Search + Pagination
        $roles = Role::with('permissions:id,name') // ⬅️ penting
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->through(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name'),
                ];
            })
            ->withQueryString();

        // semua permissions untuk select
        $allPermissions = Permission::orderBy('name')
            ->get(['id', 'name']);

        // 📦 Group permissions (checkbox section)
        $groups = Permission::orderBy('name')
            ->get()
            ->groupBy(function ($permission) {
                return explode('.', $permission->name)[0]; // ambil prefix
            })
            ->map(function ($permissions, $groupName) {
                return [
                    'group' => $groupName,
                    'permissions' => $permissions->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'name' => $p->name,
                            'guard_name' => $p->guard_name,
                        ];
                    })->values()
                ];
            })
            ->values();

        // 👤 For "Attach Users to Role"
        $users = User::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'email']);

        return Inertia::render('Admin/Role/Index', [
            'roles' => $roles,
            'allPermissions' => $allPermissions,
            'filters' => $request->only(['search']),
            'groups' => $groups,
            'users' => $users,
        ]);
    }

    // 🟢 Create Role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions);

        return back()->with('success', 'Role created');
    }

    // 🟡 Update Role
    // public function update(Request $request, $id)
    // {
    //     $role = Role::findOrFail($id);

    //     $request->validate([
    //         'name' => "required|unique:roles,name,{$id}",
    //     ]);

    //     $role->update([
    //         'name' => $request->name,
    //     ]);

    //     return back()->with('success', 'Role updated successfully.');
    // }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions);

        return back()->with('success', 'Role updated');
    }

    // 🔴 Delete Role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }

    // 🔄 Assign Permissions to Role
    public function syncPermissions(Request $request)
    {
        $request->validate([
            'role_id' => ['required'],
            'permission_ids' => ['array'],
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->syncPermissions($request->permission_ids ?? []);

        return back()->with('success', 'Role permissions updated.');
    }

    // 👤 Assign Users to Role
    public function syncUsers(Request $request)
    {
        $request->validate([
            'role_id' => ['required'],
            'user_ids' => ['array'],
        ]);

        $role = Role::findOrFail($request->role_id);

        // Detach all, then attach selected
        $role->users()->sync($request->user_ids ?? []);

        return back()->with('success', 'Role users updated.');
    }
}