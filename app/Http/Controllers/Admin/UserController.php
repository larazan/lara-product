<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $page = 10;
        $sort = 'asc';
        $users = User::when($request->q, function ($query, $search) {
            $query->where('username', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->with('roles:id,name', 'permissions:id,name')
            ->paginate($page)
            ->withQueryString();

        $roles = Role::with('permissions:id,name') // ⬅️ penting
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Admin/User/Index', [
            'page' => $page,
            'users' => $users,
            'search' => $request->only('q'),
            'roles' => $roles,
            'permissions' => Permission::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function updateRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        // Sinkronisasi menggunakan Spatie package
        $user->syncRoles($request->input('roles', []));
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->back()->with('success', 'Roles and permissions updated successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'username' => 'required',
            'email' => 'required',
        ]);

        $first_name = $request->firstName;
        $last_name = $request->lastName;
        $username = $request->username;
        $email = $request->email;

        User::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'email' => $email,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'username' => 'required',
            'email' => 'required',
        ]);

        $user = User::find($id);

        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;
        $user->username = $request->username;
        $user->email = $request->email;

        $user->update();
        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function syncRoles(Request $request, User $user)
    {
        $user->syncRoles($request->roles);
        return back()->with('success', 'Roles updated.');
    }

    public function syncPermissions(Request $request, User $user)
    {
        $user->syncPermissions($request->permissions);
        return back()->with('success', 'Permissions updated.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:users,id'],
        ]);

        User::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }

    public function role(Request $request, User $user)
    {
        Gate::authorize('modifyRole', $user);

        $request->validate(['role' => 'string|required']);

        $user->update(['role' => $request->role]);

        return redirect()
            ->route('admin.user.index')
            ->with('status', "User role changed to {$request->role} successfully.");
    }
}