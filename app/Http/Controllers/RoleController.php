<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view roles')->only('index');
        $this->middleware('permission:create roles')->only('store');
        $this->middleware('permission:edit roles')->only('update');
        $this->middleware('permission:delete roles')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $roles = Role::with('permissions')->withCount('permissions')->orderBy('name')->get();

        $permissionsByModule = Permission::orderBy('name')->get()
            ->groupBy(fn (Permission $permission) => Str::after($permission->name, ' '));

        return view('roles.index', compact('roles', 'permissionsByModule'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
            $role->syncPermissions($this->permissionIds($request));
        });

        return redirect()->route('roles.index')->with('status', 'Role created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        DB::transaction(function () use ($request, $role) {
            $role->update(['name' => $request->name]);
            $role->syncPermissions($this->permissionIds($request));
        });

        return redirect()->route('roles.index')->with('status', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if ($role->name === 'Admin') {
            return redirect()->route('roles.index')->with('error', "The Admin role can't be deleted.");
        }

        $role->delete();

        return redirect()->route('roles.index')->with('status', 'Role deleted successfully.');
    }

    /**
     * Permission IDs arrive as form strings; Spatie's syncPermissions() only
     * resolves ints as IDs and otherwise treats the value as a permission name.
     *
     * @return array<int, int>
     */
    private function permissionIds(StoreRoleRequest|UpdateRoleRequest $request): array
    {
        return array_map('intval', $request->input('permissions', []));
    }
}
