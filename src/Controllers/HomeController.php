<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\MultiAuth\Model\Admin;
use Anacreation\MultiAuth\Model\AdminPermission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getProfile(Request $request, Admin $admin) {
        $this->authorize('profile', $admin);

        return view('cms::admin.profile', compact('user'));
    }

    public function putProfile(Request $request, Admin $admin) {
        $this->authorize('updatePassword', $admin);

        $request->user()->permissions->each(function (
            AdminPermission $permission
        ) {
            dump($permission->code);
        });


        $validatedData = $this->validate($request, [
            'name'     => 'required',
            'email'    => 'nullable|unique:administrators,email,' .
                          $request->user()->id,
            'password' => 'nullable|confirmed',
        ]);

        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        } else {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $admin->update($validatedData);

        return redirect("/admin");
    }
}
