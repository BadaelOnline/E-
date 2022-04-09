<?php
/**
 * GeneralTrait
 *
 * @category  Class
 * @package   Trait
 * @author    Fahed <fahed8592@gmail.com>
 * @copyright Copyright 2021 Al-Bada el Software, Inc. All rights reserved.
 * @license   Al-Bada el Software General Public License version 2 or later; see LICENSE
 * @link      http://Al-Badael.com
 * @php-
 */

/**
 * GeneralTrait
 *
 * GeneralTrait to retrieve application wide
 * URLs based on active webinstance.
 *
 * @category    Class
 * @package     EndpointHelper
 * @author      Brian Smith <brian.smith@company.com>
 * @copyright   Copyright 2015 Company, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        http://company.com
 *
 * @since   1.0.1
 */

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait GeneralTrait
{
    /**
     * Author
     * @param $perm
     * @param $user
     * @return bool
     */
    public function author($perm, $user): bool
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

    /**
     * Return Error
     * @param $stateNum
     * @param $msg
     * @return JsonResponse
     */
    public function returnError($stateNum, $msg): JsonResponse
    {
        return response()->json(
            [
                'status' => false,
                'stateNum' => $stateNum,
                'msg' => $msg
            ])->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }

    /**
     * Return Success Message
     * @param $stateNum
     * @param $msg
     * @return JsonResponse
     */
    public function returnSuccessMessage($msg, $stateNum): JsonResponse
    {
        return response()->json(
            [
                'status' => true,
                'stateNum' => $stateNum,
                'msg' => $msg
            ])->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }

    /**
     * Return Data
     * @param $key
     * @param $value
     * @param $msg
     * @return JsonResponse
     */
    public function returnData($key, $value, $msg): JsonResponse
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

    /**
     * Data Response
     * @param $data
     * @return JsonResponse
     */
    public function dataResponse($data): JsonResponse
    {
        return response()->json(['content' => $data], ResponseAlias::HTTP_OK);
    }

    /**
     * Success Response
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse(string $message, int $code = ResponseAlias::HTTP_OK): JsonResponse
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
    public function errorResponse($message, int $code = ResponseAlias::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * Upload Image
     * @return string
     */
    public function uploadImage($image, $folder)
    {
//         $folder = public_path('images/attachments/stores' . '/' . $id . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        //  $imageUrl='images/attachments/stores/' . $id  . '/' .  $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder, $filename);
        return $filename;
    }

    public function uploadWithId($image, $id, $folder)
    {
        $filename = time() . '.' . $image->getClientOriginalName();
        $imageUrl[] = 'images/products/' . $id . '/' . $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder, $filename);
        return $filename;
    }
}
