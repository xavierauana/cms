<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\Cms\Controllers;


use Anacreation\Cms\Exceptions\NoAuthenticationException;
use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\RequestParser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoutesController extends Controller
{
    public function resolve(Request $request, RequestParser $parser) {

        $this->setLocale();

        if ($request->ajax()) {
            return $this->parseAjax();
        }

        return $this->parseView($request, $parser);

    }

    private function setLocale(): void {

        if (!session()->has("id")) {
            session()->put("id", str_random(64));
        }

        if (session()->has('locale')) {
            $sessionLocale = session()->get('locale');

            $locale = in_array($sessionLocale,
                Language::active()->pluck('code')
                        ->toArray()) ? $sessionLocale : optional(Language::whereIsActive(true)
                                                                         ->first())->code;

            app()->setLocale($locale);
        }
    }

    private function constructView(Page $page, $vars): View {
        return view("themes." . config('cms.active_theme') . ".layouts." . ".{$page->template}",
            $vars);
    }

    /**
     * @param \Anacreation\Cms\Models\Page $page
     * @return mixed
     */
    private function pageHasPermissionControl(Page $page): bool {
        return !!$page->permission;
    }

    /**
     * @param \Illuminate\Http\Request     $request
     * @param \Anacreation\Cms\Models\Page $page
     * @return mixed
     */
    private function userHasPagePermission(Request $request, Page $page
    ): bool {
        return $request->user()
                       ->hasPermission($page->permission->code);
    }

    /**
     * @param \Illuminate\Http\Request                $request
     * @param \Anacreation\Cms\Services\RequestParser $parser
     * @return \Illuminate\View\View
     * @throws \Anacreation\Cms\Exceptions\NoAuthenticationException
     * @throws \Anacreation\Cms\Exceptions\PageNotFoundHttpException
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    private function parseView(Request $request, RequestParser $parser) {
        if ($vars = $parser->parse($request)) {

            $page = $vars['page'];

            if (!$page->is_restricted) {
                return $this->constructView($page, $vars);
            }

            if (Auth::guest()) {
                throw new NoAuthenticationException("You are not allowed to visit the page!");
            }

            if ($this->pageHasPermissionControl($page)) {
                if ($this->userHasPagePermission($request, $page)) {
                    return $this->constructView($page, $vars);
                }
                throw new UnAuthorizedException("You are not allowed to visit the page!");
            }

            return $this->constructView($page, $vars);
        }

        throw new PageNotFoundHttpException();
    }

    /**
     * @throws \Anacreation\Cms\Exceptions\PageNotFoundHttpException
     */
    private function parseAjax() {
        throw new PageNotFoundHttpException();
    }
}