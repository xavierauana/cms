<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\Cms\Controllers;


use Anacreation\Cms\Exceptions\NoAuthenticationException;
use Anacreation\Cms\Exceptions\NoModuleException;
use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use Anacreation\Cms\Exceptions\UnAuthorizedException;
use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\TemplateParser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModulesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Anacreation\Cms\Exceptions\NoAuthenticationException
     * @throws \Anacreation\Cms\Exceptions\PageNotFoundHttpException
     * @throws \Anacreation\Cms\Exceptions\UnAuthorizedException
     * @throws \Anacreation\Cms\Exceptions\NoModuleException
     */
    public function resolve(Request $request) {

        if (!$pageId = $request->get('page') or
            !$page = Page::active()->find($pageId)) {
            throw new PageNotFoundHttpException();
        }

        if ($page->is_restricted and Auth::guard('web')->guest()) {
            throw new NoAuthenticationException("You are not allowed to visit the page!");
        }

        if ($page->permission and !Auth::guard('web')
                                       ->user()
                                       ->hasPermission($page->permission->code)) {
            throw new UnAuthorizedException("You are not allowed to visit the page!");
        }

        $parser = new TemplateParser();
        $definitions = $parser->loadTemplateDefinition($page->template, "");

        $targetNode = null;
        foreach ($definitions->model as $node) {
            if ((string)$node->name === $request->get('name')) {
                $targetNode = $node;
                break;
            }
        }

        if (is_null($targetNode)) {
            throw new NoModuleException();
        }

        $class = (string)($targetNode->class);

        $method = $request->query('method');

        $result = $method ? app($class)->$method($request) : (app($class))($request);

        return $request->ajax() ? response()->json($result) : $result;

    }

}