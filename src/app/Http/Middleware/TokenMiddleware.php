<?php

namespace App\Http\Middleware;

use App\Facades\ApiResponse;
use App\Http\Responders\FieldError;
use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Token;



class TokenMiddleware
{

    /**
     * @var JWTAuth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  JWTAuth  $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = $this->auth;
        try {
            $fromHeader = $request->headers->get('X-ACCESS-TOKEN');
            $token = new Token($fromHeader);
            $payload = $auth::manager()->decode($token)->toArray();

            if ($payload['type'] !== 'access') {
                throw new TokenInvalidException();
            }

            if (!$user = $auth::setToken($token)->authenticate()) {
                return ApiResponse::error(
                    [new FieldError('001-001', 'User not found', 'token')],
                    '001-001',
                    'User not found',
                    400
                );
            }

        } catch (TokenExpiredException $e) {
            return ApiResponse::error(
                [new FieldError('001-001', 'Access token is expired', 'accessToken')],
                '001-001',
                'Access token is expired',
                $e->getStatusCode()
            );

        } catch (TokenInvalidException $e) {
            return ApiResponse::error(
                [new FieldError('001-004', 'Access token invalid', 'accessToken')],
                '001-004',
                'Access token invalid',
                $e->getStatusCode()
            );

        } catch (JWTException $e) {
            return ApiResponse::error(
                [new FieldError('001-004', 'Access token is absent', 'accessToken')],
                '001-004',
                'Access token is absent',
                $e->getStatusCode()
            );
        }

        return $next($request);
    }
}
