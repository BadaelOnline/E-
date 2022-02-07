<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

trait GeneralTrait
{
    public function author($perm, $user)
    {
        $roles = $user->roles()->with('Permission')->get();
        foreach ($roles as $role) {
            $permission = $role->permission->where('slug', $perm)->first();
        }
        if (isset($permission)) {
            return true;
        } else
            return false;
    }

    public function returnError($stateNum, $msg)
    {
        return response()->json([
            'status' => false,
            'stateNum' => $stateNum,
            'msg' => $msg
        ])->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }

    public function returnSuccessMessage($msg, $stateNum)
    {
        return response()->json(
            ['status' => true,
                'stateNum' => $stateNum,
                'msg' => $msg
            ])->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }

    public function returnData($key, $value, $msg)
    {
        return response()->json(
            [
                $key => $value
                , 'status' => true,
                'stateNum' => '201',
                'msg' => $msg
            ]
        )->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }

    public function returnPlanData($key, $value, $msg)
    {
        return response()->json(
            [
                $key => $value
                , 'status' => true,
                'stateNum' => '201',
                'msg' => $msg
            ]
        )->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }

    public function insert1($model1, $Arr1)
    {
        $this->model1 = $model1;
        $int = $this->model1::insertGetId($Arr1);
        return $int;
    }

    public function insert2($model2, $Arr2)
    {
        $this->model2 = $model2;
        $trans = $this->model2::insert($Arr2);
        return $this->returnData('Store', $Arr2, 'done');
    }

    /**
     * Data Response
     * @param $data
     * @return JsonResponse
     */
    public function dataResponse($data): JsonResponse
    {
        return response()->json(['content' => $data], Response::HTTP_OK);
    }

    /**
     * Success Response
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse(string $message, $code = Response::HTTP_OK)
    {
        return response()->json(['success' => $message, 'code' => $code], $code);
    }

    /**
     * Error Response
     * @param $message
     * @param int $code
     * @return JsonResponse
     *
     */
    public function errorResponse($message, $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    public function uploadImage($folder, $image)
    {
        $image->store('/', $folder);
        $filename = $image->hashName();
        $path = 'images/' . $folder . '/' . $filename;
        return $path;
    }
}
