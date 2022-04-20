<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Role;
use App\Models\Admin\TransModel\UserTranslation;
use App\Models\User;
use App\Service\Admin\Auth\AuthService;
use App\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GeneralTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    private $userModel;
    private $roleModel;
    private $userTranslation;
    private $authService;
    public function __construct(User $userModel , AuthService $authService , Role $roleModel
        , UserTranslation $userTranslation)
    {
        $this->userModel=$userModel;
        $this->authService=$authService;
        $this->roleModel=$roleModel;
        $this->userTranslation=$userTranslation;
        // $this->middleware('auth:api', ['except' => ['login','register']]);
        // $this->middleware('auth:api', ['except' => ['login','register']]);
//        $this->middleware('guest');

    }

     public function indexLogin(){
        return view('layouts.authentication.login');
     }

     public function indexRegister(){
        return view('layouts.authentication.register');
     }

    public function login(Request $request)
    {
        return $this->authService->login( $request);
    }
    
    public function register(Request $request)
    {
        return $this->authService->register($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);

    }

    public function profile()
    {
        return $this->authService->profile();
    }

    public function refresh()
    {
        return $this->authService->refresh();
    }
    
    public function get_user(Request $request)
    {
        return $this->authService->get_user($request);
    }

    public function get_dashboard(){
        return view('layouts.admin');
    }
}
