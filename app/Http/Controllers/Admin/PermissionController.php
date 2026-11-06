<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionController extends Controller
{
    //
    public function index(Request $request)
    {
        // 🔎 Search + Pagination (for main permission table)
        $permissions = Permission::with('roles') // ⬅️ penting
            ->when($request->q, function ($query, $q) {
                $query->where('name', 'like', '%' . $q . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();


        // 📌 For Assign Permission to Role modal
        $roles = Role::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Admin/Permission/Index', [
            'permissions' => $permissions,
            'filters' => $request->only(['search']),
            'roles' => $roles,
            'search' => $request->only('q'),
        ]);
    }

    // 🟢 Create Permission
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:permissions,name'],
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        return back()->with('success', 'Permission created successfully.');
    }

    // 🟡 Update Permission
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => "required|unique:permissions,name,{$id}",
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Permission updated successfully.');
    }

    // public function show(Permission $permission)
    // {
    //     return Inertia::render('Permissions/ShowModal', [
    //         'permission' => $permission,
    //         'roles' => $permission->roles->map(function ($role) {
    //             return [
    //                 'id' => $role->id,
    //                 'name' => $role->name,
    //             ];
    //         }),
    //     ]);
    // }

    // 🔴 Delete Permission
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return back()->with('success', 'Permission deleted successfully.');
    }

    // 🔄 Assign multiple permissions to a role
    public function syncRolePermissions(Request $request)
    {
        $request->validate([
            'role_id' => ['required'],
            'permission_ids' => ['array'],
        ]);

        $role = Role::findOrFail($request->role_id);

        // Sync!
        $role->syncPermissions($request->permission_ids ?? []);

        return back()->with('success', 'Permissions updated for role.');
    }
}