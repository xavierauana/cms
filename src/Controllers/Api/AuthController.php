<?php
/**
 * Author: Xavier Au
 * Date: 9/1/2018
 * Time: 3:03 PM
 */

namespace Anacreation\Cms\Controllers\Api;


use Anacreation\Cms\Services\ApiAuthentication;
use Anacreation\Cms\Services\ApiAuthStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticate(Request $request): JsonResponse {

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){

        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticated(Request $request): JsonResponse {

        $status = ApiAuthentication::isAuthenticated();

        if ($status !== ApiAuthStatus::AUTHENTICATED) {
            return response()->json('not login', 401);
        }

        return response()->json(['user' => $request->user()]);

    }

}