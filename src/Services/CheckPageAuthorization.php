<?php
/**
 * Author: Xavier Au
 * Date: 13/12/2019
 * Time: 3:19 PM
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\Contracts\CmsPageInterface;
use Anacreation\Cms\Exceptions\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class CheckPageAuthorization
{
    /**
     * @param \Anacreation\Cms\Contracts\CmsPageInterface $page
     * @param string                                      $guard
     * @throws \Anacreation\Cms\Exceptions\AuthenticationException
     */
    public function check(CmsPageInterface $page, string $guard = 'web'): void {
        if( !$page->isRestricted()) {
            return;
        }

        /** @var \App\User $user */
        if($user = Auth::guard($guard)->user()) {
            $pagePermission = $page->getPermission();

            if($pagePermission === null or $user->hasPermission($pagePermission->code)) {
                return;
            }
        }

        throw new AuthenticationException("You are not allowed to visit the page!");

    }
}