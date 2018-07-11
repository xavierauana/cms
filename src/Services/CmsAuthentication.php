<?php
/**
 * Author: Xavier Au
 * Date: 19/6/2018
 * Time: 11:32 AM
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\Contracts\CmsAuthenticationInterface;
use App\User;
use Illuminate\Support\Facades\Auth;

class CmsAuthentication implements CmsAuthenticationInterface
{

    public function authenticate(
        array $credentials, string $guard = null, array $params = []
    ): bool {

    }

    public function login(User $user, string $guard = null) {
        $guard ? Auth::guard($guard)->login($user) : Auth::login($user);
    }
}