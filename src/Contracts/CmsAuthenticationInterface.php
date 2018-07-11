<?php
/**
 * Author: Xavier Au
 * Date: 20/6/2018
 * Time: 11:27 AM
 */

namespace Anacreation\Cms\Contracts;


use App\User;

interface CmsApiAuthenticationInterface
{
    public function authenticate(
        array $credentials, string $guard = null, array $params = []
    ): bool;

    public function login(User $user, string $guard = null);

}