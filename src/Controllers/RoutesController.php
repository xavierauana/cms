<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\Cms\Controllers;


use Anacreation\Cms\Contracts\RequestParserInterface;
use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\RequestParser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoutesController extends CmsBaseController
{
    public function resolve(Request $request) {

        if (config("cms.single_login_session", false) == true) {
            $this->checkUserSessions($request);
        }

        return $this->toResponse($request, new RequestParser);

    }

    /**
     * @param \Illuminate\Http\Request                          $request
     * @param \Anacreation\Cms\Contracts\RequestParserInterface $parser
     * @return \Illuminate\View\View
     * @throws \Anacreation\Cms\Exceptions\NoAuthenticationException
     * @throws \Anacreation\Cms\Exceptions\PageNotFoundHttpException
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     */
    protected function toResponse(
        Request $request, RequestParserInterface $parser
    ) {

        $vars = $parser->parse($request);
        $page = $this->getPage($vars);

        if (!$page) {
            throw new PageNotFoundHttpException();
        }

        if (!$page->is_restricted or $this->userHasPagePermission($page)) {
            return $this->constructView($page, $vars);
        }

        throw new UnAuthorizedException("You are not allowed to visit the page!");
    }


    private function constructView(Page $page, $vars): View {
        return view("themes." . config('cms.active_theme') . ".layouts." . ".{$page->template}",
            $vars);
    }

    /**
     * @param array|null $vars
     * @return \Anacreation\Cms\Models\Page|null
     */
    protected function getPage(?array $vars): ?Page {

        if (is_null($vars)) {
            return null;
        }

        return $page = $vars['page'] ?? null;
    }

}