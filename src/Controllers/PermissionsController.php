<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\Cms\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Anacreation\Cms\Models\Permission $permission
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Permission $permission) {
        $this->authorize('index', $permission);

        $permissions = $permission->all();

        return view("cms::admin.permissions.index", compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Anacreation\Cms\Models\Permission $permission
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Permission $permission) {
        $this->authorize('create', $permission);

        return view("cms::admin.permissions.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Permission $permission) {
        $this->authorize('create', $permission);

        $validatedData = $this->validate($request, [
            'label' => 'required',
            'code'  => 'required|unique:permissions',
        ]);

        $permission->create($validatedData);

        return redirect()->route("permissions.index");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission) {
        $this->authorize('show', $permission);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission) {
        $this->authorize('edit', $permission);

        return view("cms::admin.permissions.edit", compact("permission"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Permission          $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission) {
        $this->authorize('edit', $permission);

        $validatedData = $this->validate($request, [
            'label' => 'required',
            'code'  => [
                'required',
                Rule::unique('permissions')
                    ->ignore($permission->id, 'id')
            ],
        ]);

        $permission->update($validatedData);

        return redirect()->route("permissions.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission) {
        $this->authorize('delete', $permission);

        $permission->delete();

        return response()->json(['status' => 'completed']);
    }
}
