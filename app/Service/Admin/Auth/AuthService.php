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
    public function __construct(User $userModel , Role $roleModel
        , UserTranslation $userTranslation)
    {
        $this->userModel=$userModel;
        $this->roleModel=$roleModel;
        $this->userTranslation=$userTranslation;
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        try{
            $credentials = $request->only('email', 'password');
            $token = auth('user-api')->attempt($credentials);
            if (! $token ) {
                return $this->returnError('401', 'Unauthorized');
            }
//            $user_id = Auth::guard('user-api')->user();
            $user_id = Auth::guard('user-api')->user()->getAuthIdentifier();
            $user =$this->userModel->with('TypeUser')->find($user_id);
////             $user = auth('api')->user();
//
//            $types=$user->TypeUser()->get();
//            foreach ($types as $type){
//                 $name[] = $type['name'];
//
//                switch ($type['name']){
//                    case 'doctor':
//                        return redirect()->route('doctor.dashboard')->with($token);
//                        break;
//                    case 'admin_store':
//                        return [$token,redirect()->route('store_dashboard')];
//                        break;
//                    default:
//                        return route('profile');
//                }
//            }
//           $user->api_token = $token;
            //return token
            return $this->returnData('user', [$user,$token],'Done');
//            return $this->respondWithToken($token);
        }catch(\Throwable $ex){
            if ($ex instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->returnError('401', 'TokenInvalidException');
            }else if ($ex instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->returnError('401', 'TokenInvalidException');
            } else if ( $ex instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                return $this->returnError('401', $ex->getMessage());
            }
        }
    }
    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return response()->json(auth('user-api')->user());
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $token = $request -> token;
        if($token){
            try {

                auth('user-api')->setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('','some thing went wrongs'.$e->getMessage());
            }
            return $this->returnSuccessMessage('Logged out successfully','200');
        }else{
            $this -> returnError('','some thing went wrongs');
        }
    }
    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('user-api')->refresh());
    }
    public function register(Request $request)
    {
        $user=$this->userModel->create([
            'age' => $request->age,
            'location_id' => $request->location_id,
            'social_media_id' => $request->social_media_id,
            'is_active' => $request->is_active,
            'image' => $request->image,
            'email' => $request->email,
            'password' =>bcrypt($request->password)
        ]);
        $userid=$user->id;
        if (isset($allusers) && count($allusers)) {
            //insert other traslations for users
            foreach ($allusers as $alluser) {
                $transUser_arr[] = [
                    'first_name' => $alluser ['first_name'],
                    'last_name' => $alluser ['last_name'],
                    'local' => $alluser['local'],
                    'user_id' => $userid
                ];
            }
            $this->userTranslation->insert($transUser_arr);
        }
        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }
    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['Employee' => $user]);
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('user-api')->factory()->getTTL() * 60,
            'user' => auth('user-api')->user()
        ]);
    }
}
