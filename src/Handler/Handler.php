<?php
/**
 * Author: Xavier Au
 * Date: 15/4/2018
 * Time: 12:48 PM
 */

namespace Anacreation\Cms\Handler;

use Anacreation\Cms\Controllers\RoutesController;
use Anacreation\Cms\Exceptions\PageNotFoundHttpException;
use Anacreation\Cms\Services\RequestParser;
use App\Exceptions\Handler as ExceptionHandler;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        PageNotFoundHttpException::class
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception) {

        if ($exception instanceof NotFoundHttpException) {

            try {
                /** @var RoutesController $controller */
                $controller = app(RoutesController::class);

                /**
                 * Parsed html string
                 * @var string $result
                 */
                $result = $controller->resolve($request,
                    app(RequestParser::class));

                return response($result);
            } catch (Exception $exception) {
                return parent::render($request, $exception);
            }
        }

        return parent::render($request, $exception);
    }
}