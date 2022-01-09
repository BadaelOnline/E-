<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Employee;
use App\Models\Admin\TransModel\EmployeeTranslation;
use App\Models\User;
use App\Service\Admin\Auth\EmployeeAuthService;
use App\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmployeeAuthController extends Controller
{
    use GeneralTrait;

    private $employeeAuthService;

    public function  __construct(EmployeeAuthService $employeeAuthService)
    {
        $this->employeeAuthService=$employeeAuthService;

//        $this->middleware('auth:api', ['except' => ['authenticate', 'register']]);
//        $this->middleware('assign.guard:employee-api');
//        $this->middleware('auth:employee-api')->except('authenticate');
//        Config::set('jwt.api', Employee::class);
//        Config::set('auth.providers', ['api' => [
//            'driver' => 'eloquent',
//            'model' => Employee::class,
//        ]]);
    }
    public function login(Request $request)
    {
        return $this->employeeAuthService->login( $request);
    }
    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return $this->employeeAuthService->me();
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        return $this->employeeAuthService->logout($request);

    }
    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->employeeAuthService->refresh();
    }
    public function register(Request $request)
    {
        return $this->employeeAuthService->register($request);
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    public function get_user(Request $request)
    {
        return $this->employeeAuthService->get_user($request);
    }
}
