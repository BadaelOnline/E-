<?php

namespace App\Exceptions;

use App\Traits\GeneralTrait;
use HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use GeneralTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $exception) {
            //
        });
//        $this->renderable(function(TokenInvalidException $e, $request){
//            return Response::json(['error'=>'Invalid token'],401);
//        });
//        $this->renderable(function (TokenExpiredException $e, $request) {
//            return Response::json(['error'=>'Token has Expired'],401);
//        });
//
//        $this->renderable(function (JWTException $e, $request) {
//            return Response::json(['error'=>'Token not parsed'],401);
//        });
//        $this->reportable(function (JWTException $exception) {
//            return $this->returnError(403,'unauthorized,Please Login Or Register');
//        });
//        $this->renderable(function (TokenExpiredException $ex){
//                return $this->returnError(403,'unauthorized');
//        });
//        $this->renderable(function (JWTException $ex){
//                 return $this->returnError(403,'unauthorized,Please Login Or Register');
////        });
//        $this->renderable(function (Throwable $e) {
//            return $this->handleException($e);
//        });
    }
    public function handleException( Throwable $e){
        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            $defaultMessage = \Symfony\Component\HttpFoundation\Response::$statusTexts[$code];
            $message = $e->getMessage() == "" ? $defaultMessage : $e->getMessage();
            return $this->errorResponse($message, $code);
        } else if ($e instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($e->getModel()));
            return $this->errorResponse("Does not exist any instance of {$model} with the given id", Response::HTTP_NOT_FOUND);
        } else if ($e instanceof AuthorizationException) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_FORBIDDEN);
        } else if ($e instanceof TokenBlacklistedException) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        } else if ($e instanceof AuthenticationException) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        } else if ($e instanceof ValidationException) {
            $errors = $e->validator->errors()->getMessages();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else if ($e instanceof RouteNotFoundException) {
            $errors = $e->getMessage();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }else if ($e instanceof TokenInvalidException) {
            $errors = $e->getMessage();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }else if ($e instanceof TokenExpiredException) {
            $errors = $e->getMessage();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }else if ($e instanceof JWTException) {
            $errors = $e->getMessage();
            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        else {
            if (config('app.debug'))
                return $this->dataResponse($e->getMessage());
            else {
                return $this->errorResponse('Try later', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}
