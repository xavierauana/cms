<?php

namespace Anacreation\Cms\Controllers;

use Anacreation\MultiAuth\Model\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getProfile(Request $request) {
        $user = $request->user();

        return view('cms::admin.profile', compact('user'));
    }

    public function putProfile(Request $request, Admin $admin) {
        if ($request->user()->id === $admin->id) {
            $validatedData = $this->validate($request, [
                'name'     => 'required',
                'password' => 'nullable|confirmed',
            ]);
            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            } else {
                $validatedData['password'] = bcrypt($validatedData['password']);
            }

            $admin->update($validatedData);

        } else {
            dd('not same user');
        }

        return redirect("/admin");
    }
}
