<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\MultiAuth\Model\AdminPermission;
use Anacreation\MultiAuth\Model\AdminRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdministratorRolesController extends Controller
{
    public function index(Request $request) {
        $this->authorize('index', new AdminRole);

        $roles = AdminRole::all();

        return view("cms::admin.administratorRoles.index",
            compact('roles'));
    }

    public function edit(AdminRole $adminRole) {
        $this->authorize('edit', $adminRole);
        $adminRole->load('permissions');
        $permissions = AdminPermission::all();

        return view("cms::admin.administratorROles.edit",
            [
                'role'        => $adminRole,
                'permissions' => $permissions,
            ]);
    }

    public function update(Request $request, AdminRole $adminRole) {
        $this->authorize('update', $adminRole);

        $validatedData = $this->validate($request, [
            'label'         => 'required',
            'permissions'   => 'nullable',
            'permissions.*' => 'nullable|exists:admin_permissions,id',
        ]);

        $permissionIds = $validatedData['permissions'];

        unset($validatedData['permissions']);

        $adminRole->update($validatedData);

        $adminRole->permissions()->sync($permissionIds);

        return redirect()->route("admin_roles.index")
                         ->withStatus('Admin role updated!');
    }

    public function create(Request $request) {
        $this->authorize('create', new AdminRole);
        $permissions = AdminPermission::all();

        return view("cms::admin.administratorRoles.create",
            compact('permissions'));
    }

    public function store(Request $request, AdminRole $adminRole) {
        $this->authorize('store', $adminRole);

        $validatedData = $this->validate($request, [
            'label'         => 'required',
            'code'          => 'required|unique:admin_roles',
            'permissions'   => 'nullable',
            'permissions.*' => 'nullable|exists:admin_permissions,id',
        ]);

        $newRole = $adminRole->create($validatedData);

        $permissionIds = $validatedData['permissions'] ?? null;

        if ($permissionIds) {
            $newRole->permissions()->sync($permissionIds);
            unset($validatedData['permissions']);
        }

        return redirect()->route("admin_roles.index")
                         ->withStatus('New admin role created!');
    }

    public function destroy(AdminRole $adminRole) {
        $this->authorize('delete', $adminRole);

        $adminRole->delete();

        return response()->json("Admin role:  {$adminRole->label} deleted!");
    }
}
