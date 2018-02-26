<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\CMS\Controllers;


use Anacreation\Cms\Exceptions\NoAuthenticationException;
use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Language;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    public function resolve(Request $request) {
        if (session()->has('locale')) {
            $sessionLocale = session()->get('locale');

            $locale = in_array($sessionLocale,
                Language::active()->pluck('code')
                        ->toArray()) ? $sessionLocale : optional(Language::whereIsActive(true)
                                                                         ->first())->code;

            app()->setLocale($locale);
        }

        if ($vars = getPage($request->segments())) {

            if ($vars['page']->is_restricted) {
                if ($user = $request->user()) {
                    if ($permission = $vars['page']->permission) {
                        if ($user->hasPermission($permission->code)) {
                            return view("themes." . config('theme.active') . "/layouts/" . ".{$vars['page']->template}",
                                $vars);
                        }
                        throw new UnAuthorizedException("You are not allowed to visit the page!");
                    } else {
                        return view("themes." . config('theme.active') . "/layouts/" . ".{$vars['page']->template}",
                            $vars);
                    }
                } else {
                    throw new NoAuthenticationException("You are not allowed to visit the page!");
                }
            }


            return view("themes." . config('theme.active') . "/layouts/" . ".{$vars['page']->template}",
                $vars);
        }

        throw new PageNotFoundHttpException();
    }
}