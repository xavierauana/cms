<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\Cms\Controllers;


use Anacreation\Cms\Contracts\CmsPageInterface;
use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param int                      $pageId
     * @param string                   $name
     * @param string                   $method
     * @return \Illuminate\Http\JsonResponse
     * @throws \Anacreation\Cms\Exceptions\AuthenticationException
     * @throws \Anacreation\Cms\Exceptions\NoModuleException
     * @throws \Anacreation\Cms\Exceptions\PageNotFoundHttpException
     */
    public function resolve(Request $request, int $pageId, string $name, string $method
    ) {

        if( !$page = app(CmsPageInterface::class)->active()->find($pageId)) {
            throw new PageNotFoundHttpException();
        }

        return $page->callModel($name,
                                $method,
                                [$request]);
    }
}