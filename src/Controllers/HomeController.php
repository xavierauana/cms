<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\MultiAuth\Model\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getProfile(Request $request) {
        return view('cms::admin.profile', ['user' => $request->user()]);
    }

    public function putProfile(Request $request, Admin $admin) {
        $this->authorize('updatePassword', $admin);

        $validatedData = $this->validate($request, [
            'name'     => 'required',
            'email'    => 'nullable|unique:administrators,email,' .
                          $request->user()->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        } else {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $admin->update($validatedData);

        return redirect("/admin")->withStatus('Profile updated!');
    }
}
