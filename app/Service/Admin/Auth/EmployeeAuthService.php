<?php


namespace App\Service\Admin\Auth;


use App\Models\Admin\Employee;
use App\Models\Admin\TransModel\EmployeeTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmployeeAuthService
{
    use GeneralTrait;
    public function  __construct()
    {

    }
    public function register(Request $request)
    {
        //Validate data
        $allemployees = collect($request->employee)->all();

        $employee=Employee::create([
            'age' => $request->age,
            'location_id' => $request->location_id,
            'social_media_id' => $request->social_media_id,
            'image' => $request->image,
            'email' => $request->email,
            'is_active' => $request->is_active,
            'salary' => $request->salary,
            'certificate' => $request->certificate,
            'start_date' => $request->start_date,
            'password' =>bcrypt($request->password)
        ]);
        $employeeid=$employee->id;
        if (isset($allemployees) && count($allemployees)) {
            //insert other traslations for users
            foreach ($allemployees as $allemployee) {
                $transEmployee_arr[] = [
                    'first_name' => $allemployee ['first_name'],
                    'last_name' => $allemployee ['last_name'],
                    'local' => $allemployee['local'],
                    'employee_id' => $employeeid
                ];
            }
            EmployeeTranslation::insert($transEmployee_arr);
        }
        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully',
            'data' => $employee
        ], Response::HTTP_OK);
    }
    public function login(Request $request)
    {
//        return response(Config('user_type'));
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('employee-api')->attempt($credentials);
//            dd($request->input('token'));

            if (!$token)
                return $this->returnError('E001', 'بيانات الدخول غير صحيحة');
            $admin = Auth::guard('employee-api')->user();
            $admin->api_token = $token;
            //return token
            return $this->returnData('employee', $admin,'');

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request);

            return response()->json([
                'success' => true,
                'message' => 'Employee has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Employee cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function me()
    {
        return response()->json(auth('employee-api')->user());
    }
    public function refresh()
    {
        return $this->respondWithToken(auth('employee-api')->refresh());
    }
    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['Employee' => $user]);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('employee-api')->factory()->getTTL() * 60,
            'user' => auth('employee-api')->user()
        ]);
    }
}
