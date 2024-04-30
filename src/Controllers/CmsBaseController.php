<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\Cms\Controllers;


use Anacreation\Cms\Contracts\CmsPageInterface;
use Anacreation\Cms\Contracts\RequestParserInterface;
use Anacreation\Cms\Exceptions\AuthenticationException;
use Anacreation\Cms\Services\LanguageService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

abstract class CmsBaseController extends Controller
{
    private $activeLanguageCodes;
    private $defaultLanguageCode;

    public function __construct(Request $request)
    {
        $languageService = new LanguageService;

        $this->activeLanguageCodes = $languageService->activeLanguages->pluck('code')
            ->toArray() ?? [];

        $this->defaultLanguageCode = $languageService->defaultLanguage->code;

        $this->setLocale($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Anacreation\Cms\Services\RequestParser $parser
     * @return \Illuminate\Http\JsonResponse
     */
    abstract public function resolve(Request $request);

    abstract protected function toResponse(
        Request $request, RequestParserInterface $parser
    );

    protected function setLocale(Request $request): void
    {

        if (!session()->has("id")) {
            session()->put("id", Str::random(64));
        }

        $checkLocale = $this->getRequestLanguageCode($request);

        $locale = $this->getLocale($checkLocale);

        app()->setLocale($locale);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @throws \Anacreation\Cms\Exceptions\AuthenticationException
     */
    protected function checkUserSessions(Request $request): void
    {
        $table = "user_sessions";

        if (!Schema::hasTable($table)) {
            throw new InvalidArgumentException("No user_sessions table");
        }

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

            throw new AuthenticationException("You are not allowed to visit the page!");
        }
    }

    /**
     * @param \Anacreation\Cms\Models\Page $page
     * @param string $guard
     * @return mixed
     * @throws \Anacreation\Cms\Exceptions\AuthenticationException
     */
    protected function userHasPagePermission(
        CmsPageInterface $page, string $guard = 'web'
    ): bool
    {

        if ($user = Auth::guard($guard)->user()) {
            $permission = $page->getPermission();

            return is_null($permission) or $user->hasPermission($page->permission->code);
        }
        throw new AuthenticationException("You are not allowed to visit the page!");

    }

    /**
     * @param $checkLocale
     * @return mixed
     */
    protected function getLocale(string $checkLocale = null): string
    {

        if ($checkLocale === null) {
            return $this->defaultLanguageCode;
        }

        return in_array($checkLocale,
            $this->activeLanguageCodes) ?
            $checkLocale :
            $this->defaultLanguageCode;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    private function getRequestLanguageCode(Request $request)
    {
        if (in_array($request->segments()[0], $this->activeLanguageCodes)) {
            return $request->segments()[0];
        }

        $userLocale = $request->get('locale') ?? session()->get('locale');

        if (in_array($request->segments()[0], $this->activeLanguageCodes)) {
            return $userLocale;
        }

        return $this->defaultLanguageCode;
    }

}
