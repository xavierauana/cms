<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\Cms\Controllers\Api;


use Anacreation\Cms\Api\Resources\ApiResponseResource;
use Anacreation\Cms\Contracts\RequestParserInterface;
use Anacreation\Cms\Controllers\CmsBaseController;
use Anacreation\Cms\Services\ApiRequestParser;
use Illuminate\Http\Request;

class RoutesController extends CmsBaseController
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resolve(Request $request) {
        return $this->toResponse($request, new ApiRequestParser);
    }

    protected function toResponse(
        Request $request, RequestParserInterface $parser
    ) {
        $vars = $parser->parse($request);

        $page = $vars['page'] ?? null;

        return is_null($page) ?
            response()->json('No page found!', 404) :
            response()->json(new ApiResponseResource($page, 1));
    }
}