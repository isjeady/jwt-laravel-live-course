<?php

namespace App\Http\Middleware;


use Closure;
use JWTAuth;
use App\Utils\CustomResponse;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Exception;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return CustomResponse::setFailResponse(Lang::get('errors.token.invalid'), Response::HTTP_NOT_ACCEPTABLE);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return CustomResponse::setFailResponse(Lang::get('errors.token.expired'), Response::HTTP_NOT_ACCEPTABLE);
            } else {
                return CustomResponse::setFailResponse(Lang::get('errors.token.not_found'), Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}
