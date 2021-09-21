<?php

namespace App\Http\Middleware;

use App\Service\JWTValidator;
use Closure;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


class VerifyJWT
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
        if(!isset($_SERVER['HTTP_AUTHORIZATION'])){
            return Response::json(array('error' => 'Please set Authorization header'));  
        }
        try{
            $jwt = JWTValidator::verifyToken(trim(str_replace("Bearer", "", $_SERVER['HTTP_AUTHORIZATION'])));
            if ($jwt == null){
                throw new Exception("Invalid Token");
            }
            $request->jwt = $jwt;
            return $next($request);
        }catch(Exception $e){
            return Response::json(array('error' => $e->getMessage()), 401);  
        }
    }
}
