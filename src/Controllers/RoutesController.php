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
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\LanguageService;
use Anacreation\Cms\Services\RequestParser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use InvalidArgumentException;

class RoutesController extends Controller
{
    public function resolve(Request $request, RequestParser $parser) {

        $this->setLocale($request);

        if ($request->ajax()) {
            return $this->parseAjax();
        }

        return $this->parseView($request, $parser);

    }

    private function setLocale(Request $request): void {

        if (!session()->has("id")) {
            session()->put("id", str_random(64));
        }

        $checkLocale = $request->get('locale') ?? session()->get('locale');

        $locale = $this->getLocale($checkLocale);

        app()->setLocale($locale);
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

            if (config("cms.single_login_session", false) == true) {
                $this->checkUserSessions($request);
            }

            if ($this->pageHasPermissionControl($page)) {
                if ($this->userHasPagePermission($request,
                    $page)) {
                    return $this->constructView($page, $vars);
                }

                throw new UnAuthorizedException("You are not allowed to visit the page!");
            }
        }


        return $this->constructView($page, $vars);


        throw new PageNotFoundHttpException();
    }

    /**
     * @throws \Anacreation\Cms\Exceptions\PageNotFoundHttpException
     */
    private
    function parseAjax() {
        throw new PageNotFoundHttpException();
    }

    /**
     * @param $checkLocale
     * @return mixed
     */
    private
    function getLocale(
        string $checkLocale = null
    ): string {

        $service = (new LanguageService);
        $defaultLanguage = $service->defaultLanguage;

        if ($checkLocale === null) {
            return $defaultLanguage->code;
        }

        $activeLanguages = $service->activeLanguages;

        $locale = in_array($checkLocale,
            $activeLanguages->pluck('code')
                            ->toArray()) ? $checkLocale : $defaultLanguage->code;

        return $locale;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Anacreation\Cms\Exceptions\NoAuthenticationException
     */
    private function checkUserSessions(Request $request): void {
        $table = "user_sessions";

        if(!Schema::hasTable($table)) throw new InvalidArgumentException("No user_sessions table");

        $sessionId = $request->session()
                             ->getId();
        $userId = Auth::user()->count();
        if (DB::table($table)
              ->latest()
              ->whereUserId($userId)
              ->take(1)
              ->get()
              ->filter(function ($obj) use ($sessionId) {
                  return $obj->session == $sessionId;
              })
              ->count() === 0
        ) {
            Auth::logout();

            throw new NoAuthenticationException("You are not allowed to visit the page!");
        }
    }
}