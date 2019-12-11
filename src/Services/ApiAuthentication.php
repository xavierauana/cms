<?php
/**
 * Author: Xavier Au
 * Date: 19/6/2018
 * Time: 11:32 AM
 */

namespace Anacreation\Cms\Services;


use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuthStatus
{

    public const AUTHENTICATED = 1;
    public const EXPIRED       = 2;
    public const INVALID       = 3;
    public const ABSENT        = 4;
    public const NOTFOUND      = 5;
}

class ApiAuthentication
{
    /**
     * @param $credentials
     * @throws
     * @return String
     */
    public static function authenticate($credentials): string {
        // attempt to verify the credentials and create a token for the user

        Auth::attempt($credentials);

//        return JWTAuth::attempt($credentials) ?? null;
    }

    public static function isAuthenticated(): int {

        $token = str_replace("Bearer ", "", request()->header('authorization'));

        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {

                return ApiAuthStatus::NOTFOUND;
            }

            return ApiAuthStatus::AUTHENTICATED;

        } catch (TokenExpiredException $e) {

            return ApiAuthStatus::EXPIRED;

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {
            return ApiAuthStatus::INVALID;

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {
            return ApiAuthStatus::ABSENT;

            return response()->json(['token_absent'], $e->getStatusCode());

        }
    }
}