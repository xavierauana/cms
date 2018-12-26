<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\MultiAuth\Model\Admin;
use Anacreation\MultiAuth\Model\AdminRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdministratorsController extends Controller
{
    public function index(Request $request) {
        $administrators = Admin::with('roles')->get();

        return view("cms::admin.administrators.index",
            compact('administrators'));
    }

    public function edit(Admin $administrator) {
        $administrator->load('roles');
        $roles = AdminRole::pluck('label', 'id');

        return view("cms::admin.administrators.edit",
            [
                'admin' => $administrator,
                'roles' => $roles,
            ]);
    }

    public function update(Request $request, Admin $administrator) {
        $request->user()->can('update', $administrator);

        $validatedData = $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required|email|unique:administrators,email,' . $administrator->id,
            'roles'   => 'nullable',
            'roles.*' => 'nullable|exists:admin_roles,id',
        ]);

        $roleIds = $validatedData['roles'];

        unset($validatedData['roles']);

        $administrator->update($validatedData);

        $administrator->roles()->sync($roleIds);

        return redirect()->route("administrators.index")
                         ->withStatus('Admin updated!');
    }

    public function create(Request $request) {
        $request->user()->can('create', (new Admin));
        $roles = AdminRole::pluck('label', 'id');

        return view("cms::admin.administrators.create", compact('roles'));
    }

    public function store(Request $request, Admin $administrator) {
        $this->authorize('store', $administrator);

        $validatedData = $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:administrators,email,' . $administrator->id,
            'roles'    => 'nullable',
            'roles.*'  => 'nullable|exists:admin_roles,id',
            'password' => 'required|confirmed'
        ]);

        $administrator->name = $validatedData['name'];
        $administrator->email = $validatedData['email'];
        $administrator->password = bcrypt($validatedData['password']);
        $administrator->save();

        $roleIds = $validatedData['roles'];

        unset($validatedData['roles']);

        $administrator->roles()->sync($roleIds);

        return redirect()->route("administrators.index")
                         ->withStatus('Admin created!');
    }

    public function destroy(Admin $administrator) {
        $this->authorize('delete', $administrator);

        $administrator->delete();

        return response()->json("Admin: {$administrator->id} deleted!");
    }
}
