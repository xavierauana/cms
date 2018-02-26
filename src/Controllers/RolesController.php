<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Models\Permission;
use Anacreation\Cms\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Anacreation\Cms\Models\Role $role
     * @return void
     */
    public function index(Role $role) {
        $this->authorize('index', $role);

        $roles = $role->all();

        return view("cms::admin.roles.index", compact("roles"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Anacreation\Cms\Models\Role       $role
     * @param \Anacreation\Cms\Models\Permission $permission
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Role $role, Permission $permission) {
        $this->authorize('create', $role);

        $roles = $role->all();
        $permissions = $permission->all();

        return view("cms::admin.roles.create", compact("roles", "permissions"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param \Anacreation\Cms\Models\Role $role
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, Role $role) {
        $this->authorize('store', $role);
        $validatedData = $this->validate($request, [
            'label'         => 'required',
            'code'          => 'required|unique:roles',
            'permissions.*' => 'in:' . implode(",",
                    Permission::pluck('id')->toArray()),
        ]);

        DB::transaction(function () use ($role, $validatedData) {
            $new_role = $role->create($validatedData);
            $new_role->permissions()->sync($validatedData['permissions'] ?? []);
        });


        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role                              $role
     * @param \Anacreation\Cms\Models\Permission $permission
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role, Permission $permission) {
        $this->authorize('edit', $role);

        $permissions = $permission->all();
        $role->load('permissions');

        return view("cms::admin.roles.edit", compact("role", "permissions"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Role                     $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role) {
        $this->authorize('update', $role);

        $validatedData = $this->validate($request, [
            'label'         => 'required',
            'code'          => [
                'required',
                Rule::unique('roles')->ignore($role->id, 'id')
            ],
            'permissions.*' => 'in:' . implode(",",
                    Permission::pluck('id')->toArray()),
        ]);

        DB::transaction(function () use ($role, $validatedData) {
            $role->update($validatedData);
            $role->permissions()->sync($validatedData['permissions'] ?? []);
        });


        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role) {
        $this->authorize('delete', $role);

        $role->delete();

        return response()->json(['status' => 'completed']);
    }
}
