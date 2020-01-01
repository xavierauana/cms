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
use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\RequestParser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoutesController extends CmsBaseController
{
    public function resolve(Request $request) {
        if(config("cms.single_login_session",
                  false) == true) {
            $this->checkUserSessions($request);
        }

        $this->setLocale($request);


        return $this->toResponse($request,
                                 new RequestParser);

    }

    /**
     * @param \Illuminate\Http\Request                          $request
     * @param \Anacreation\Cms\Contracts\RequestParserInterface $parser
     * @return \Illuminate\View\View
     * @throws \Anacreation\Cms\Exceptions\AuthenticationException
     * @throws \Anacreation\Cms\Exceptions\PageNotFoundHttpException
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    protected function toResponse(
        Request $request, RequestParserInterface $parser
    ) {
        $vars = $parser->parse($request);
        /** @var \Anacreation\Cms\Contracts\CmsPageInterface $page */
        $page = $vars['page'] ?? null;
        if(is_null($page)) {

            if($redirectUri = $this->getCustomRedirect($request->path())) {
                return redirect($redirectUri);
            }

            throw new PageNotFoundHttpException();
        }

        if( !$page->isRestricted()) {
            return $this->constructView($page,
                                        $vars);
        }

        /** @var \App\User $user */
        $user = auth()->user();

        if(is_null($user)) {
            throw new AuthenticationException('The page is restricted');
        }

        $permission = $page->getPermission();

        if(is_null($permission)) {
            return $this->constructView($page,
                                        $vars);
        }

        if($user->hasPermission($permission->code)) {
            return $this->constructView($page,
                                        $vars);
        }

        throw new UnAuthorizedException('You are not allow to visit the page!');

    }


    private function constructView(CmsPageInterface $page, $vars): View {

        $viewPath = sprintf("themes.%s.layouts.%s",
                            config('cms.active_theme'),
                            $page->getTemplate());

        return view($viewPath,
                    $vars);
    }

    /**
     * @param array|null $vars
     * @return \Anacreation\Cms\Models\Page|null
     */
    protected function getPage(?array $vars): ?Page {

        if(is_null($vars)) {
            return null;
        }

        return $page = $vars['page'] ?? null;
    }

    private function getCustomRedirect(string $path): ?string {
        $customRedirectPaths = config('cms.custom_redirect',
                                      []);

        if(in_array($path,
                    array_keys($customRedirectPaths))) {
            return $customRedirectPaths[$path];
        }

        return null;
    }
}