<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckEmployeeToken
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->returnError('E300','INVALID_TOKEN'.$e->getMessage());
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->returnError('E300','EXPIRED_TOKEN '.$e->getMessage());
            }else{
                return $this->returnError('E300','AUTHORIZATION TOKEN NOT FOUND '.$e->getMessage());
            }
        }
        if(!$user)
//            return response()->json(['sucess'=>false,'msg'=>trans('UnAuthenticationUser')]);
        return $this->returnError(trans('UnAuthenticationUser'));
        return $next($request);
    }
}
