<?php


namespace App\Service\Admin\Auth;


use App\Models\Admin\Role;
use App\Models\Admin\TransModel\UserTranslation;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AuthService
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

    public function __construct(User $userModel, Role $roleModel
        , UserTranslation $userTranslation)
    {
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
        $this->userTranslation = $userTranslation;
    }


    public function indexLogin(){

    }

    public function indexRegister(){

    }

    public function login(Request $request)
    {
        try {
            $token_validity = 24 * 60;
            $this->guard()->factory()->setTTL($token_validity);
            $credentials = $request->only('email', 'password');
            if (! $token = $this->guard()->attempt($credentials)){
                return redirect()->route('index.login')->with('error', 'Unauthorized');
            }
            $cookie = cookie('jwt',$token,$token_validity);
            return redirect()->route('admin.dashboard')->with('success', 'Login successfully');
        } catch (\Exception $ex) {
            return redirect()->route('index.login')->with('error', 'faild login');
        }

        /*
        try {
            $credentials = $request->only('email', 'password');
            $token = auth('user-api')->attempt($credentials);
            if (!$token) {
                return $this->returnError('401', 'Unauthorized');
            }
            $user_id = Auth::guard('user-api')->user()->getAuthIdentifier();
            $user = $this->userModel->with('TypeUser')->find($user_id);
            return $this->returnData('user', [$user, $token], 'Done');
        } catch (\Throwable $ex) {
            if ($ex instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->returnError('401', 'TokenInvalidException');
            } else if ($ex instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->returnError('401', 'TokenInvalidException');
            } else if ($ex instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return $this->returnError('401', $ex->getMessage());
            } else if ($ex instanceof \Illuminate\Database\QueryException) {
                return $this->returnError('401', 'this email in used');
            }
        }
        */
    }

    public function register(Request $request)
    {
        try{
        $user = $this->userModel->create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'username' => $request->input('username'),
            'age' => $request->input('age'),
            'location_id' => $request->input('location_id'),
            'social_media_id' => $request->input('social_media_id'),
            'is_active' => $request->is_active = 1,
            'image' => $request->input('image'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);
        $token = JWTAuth::fromUser($user);
        return redirect()->route('index.login')->with('success', 'Registered successfully');
    } catch (\Exception $ex) {
        return $ex->getMessage();
        return redirect()->route('index.register')->with('error', 'faild login');
        }
        /*
        $user = $this->userModel->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'age' => $request->age,
            'location_id' => $request->location_id,
            'social_media_id' => $request->social_media_id,
            'is_active' => $request->is_active,
            'image' => $request->image,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $token = JWTAuth::fromUser($user);
        return $this->respondWithToken($token);
        */
    }

    public function logout(Request $request)
    {
        try {
            Cookie::forget('jwt');
            // $this->guard()->logout();
            return redirect()->route('index.login')->with('success', 'Logged out successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'some thing went wrongs');
        }
        /*
         $token = $request->token;
        if ($token) {
            try {

                auth('user-api')->setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return $this->returnError('', 'some thing went wrongs' . $e->getMessage());
            }
            return $this->returnSuccessMessage('Logged out successfully', '200');
        } else {
            $this->returnError('', 'some thing went wrongs');
        }
        */
    }

    public function profile()
    {
        return $this->guard()->user();
        // return response()->json(auth('user-api')->user());
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
        // return $this->respondWithToken(auth('user-api')->refresh());
    }

    protected function guard(){
        return Auth::guard();
    }


    public function get_user(Request $request)
    {

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['User' => $user]);
        /*
         $user = JWTAuth::authenticate($request->token);

        return response()->json(['User' => $user]);
        */
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'token_validity' => $this->guard()->factory()->getTTL() * 60,
        ]);
        /*
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('user-api')->factory()->getTTL() * 60,
            'user' => auth('user-api')->user()
        ]);
        */
    }

    public function get_dashboard(){

    }
}
