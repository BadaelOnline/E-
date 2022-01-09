<?php

namespace App\Exceptions;

use App\Traits\GeneralTrait;
//use Illuminate\Database\Eloquent\ModelNotFoundException;
use BadMethodCallException;
use Illuminate\Database\QueryException as QueryException;
//use Illuminate\Marble\Traits;
use Exception;

class GeneralHandler extends Exception
{
    use GeneralTrait;
    protected $dontReport = [
        //
    ];
      /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function render($request ,Exception $exception)
    {

            if($exception instanceof BadMethodCallException){
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => 'Modle Not Found',
                        'exception' => $exception
                    ],
                    500
                );
            }
            if ($exception instanceof QueryException)
            {
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => 'There was Issue with the Query',
                        'exception' => $exception
                    ],
                    500
                );
            }
            if ($exception instanceof \Error) {
                // $exception = $exception->getResponse();
                return $this->apiResponse(
                    [
                        'success' => false,
                        'message' => "There was some internal error",
                        'exception' => $exception
                    ],
                    500
                );
            }
            return parent::render($request,$exception);
    }
    }
