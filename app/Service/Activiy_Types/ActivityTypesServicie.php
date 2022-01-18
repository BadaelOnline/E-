<?php

namespace App\Service\Activiy_Types;

use App\Http\Requests\ActivityType\ActivityTypeRequest;
use App\Models\Activities\Activity_Type;
use App\Models\Activities\ActivityTypeTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class ActivityTypesServicie
{
    private $activity_Type;
    private $activity_typeTranslation;
    private $PAGINATION_COUNT;

    use GeneralTrait;

    public function __construct(Activity_Type $activity_Type,
                                ActivityTypeTranslation $activity_typeTranslation)
    {
        $this->activity_typeTranslation = $activity_typeTranslation;
        $this->activity_Type = $activity_Type;
    }
    /****Get All  activity_Types  ****/
    public function getAll()
    {
        try {
//            return Config::get('activities.activity');
            $activity_Types = $this->activity_Type->get();
            if (count($activity_Types) > 0) {
                return $this->returnData('activity_Type', $activity_Types, 'done');
            } else {
                return $this->returnSuccessMessage('activity_Type', 'activity_Type doesnt exist yet');
            }


        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Get Active activity_Type By ID  ***
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {
        try {
//            Gate::authorize('Read Brand');
            $activity_Type = $this->activity_Type->findOrFail($id);
            if (!isset($activity_Type)) {
                return  $this->returnSuccessMessage('This activity_Type not found', 'done');
            }
            return  $this->returnData('activity_Type', $activity_Type, 'done');
        } catch (\Exception $ex) {
            if ($ex instanceof TokenExpiredException){
                return $this->returnError('400', $ex->getMessage());
            }
            return $this->returnError('400', $ex->getMessage());

        }
    }
    /*__________________________________________________________________*/
    /****ــــــ This Functions For Trashed activity_Type  ****/
    /****Get All Trashed activity_Type Or By ID  ****/
    public function getTrashed()
    {
        try {
            $activity_Type = $this->activity_Type->where('is_active', 0)->get();

            if (count($activity_Type) > 0) {
                return $this->returnData('activity_Type', $activity_Type, 'done');
            } else {
                return $this->returnSuccessMessage('activity_Type', 'activity_Type trashed doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Restore activity_Type Fore Active status  ***
     * @param $id
     * @return JsonResponse
     */
    public function restoreTrashed($id)
    {
        try {
            $activity_Type = $this->activity_Type->find($id);
            if (is_null($activity_Type)) {
                return $response = $this->returnSuccessMessage('activity_Type', 'This activity_Type not found');
            } else {
                $activity_Type->is_active = true;
                $activity_Type->save();
                return $this->returnData('activity_Type', $activity_Type, 'This activity_Type Is trashed Now');
            }
        } catch (\Exception $ex) {
            if($ex instanceof AccessDeniedException)
                return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****   activity_Type's Soft Delete   ***
     * @param $id
     * @return JsonResponse
     */
    public function trash($id)
    {
        try {
            $activity_Type = $this->activity_Type->find($id);
            if (is_null($activity_Type)) {
                return $response = $this->returnSuccessMessage('activity_Type', 'This activity_Type not found');
            } else {
                $activity_Type->is_active = false;
                $activity_Type->save();
                return $this->returnData('activity_Type', $activity_Type, 'This activity_Type Is trashed Now');
            }

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Create activity_Type   ***
     * @return JsonResponse
     */
    public function create(ActivityTypeRequest $request)
    {
        try {
            $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            /** transformation to collection */
            $allActivityTypes = collect($request->activity_type)->all();
            DB::beginTransaction();
            /** create the default language's ActivityType **/
             $unTransActivityType_id = $this->activity_Type->insertGetId([
                'is_active' => $request['is_active'],
                'activity_id'=> $request['activity_id']
            ]);
            /** check the ActivityType and request */
            if (isset($allActivityTypes) && count($allActivityTypes)) {
                /**  insert other translations for ActivityType */
                foreach ($allActivityTypes as $allActivityType) {
                     $transactivityType_arr[] = [
                        'name' => $allActivityType ['name'],
                        'local' => $allActivityType['local'],
                        'activity_type_id' => $unTransActivityType_id,
                    ];
                }
                 $this->activity_typeTranslation->insert($transactivityType_arr);
            }
            DB::commit();
            return $this->returnData('Activity_Type', [$unTransActivityType_id,$allActivityTypes], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('Activity_Type', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Update activity_Type   ***
     * @param ActivityTypeRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(ActivityTypeRequest $request, $id)
    {
        $request->validated();
        try {
            $activity_Type = $this->activity_Type->find($id);
            if (!$activity_Type)
                return $this->returnError('400', 'not found this activities_type');
            if (!($request->has('activities_type.is_active')))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);
            $unTransActivityType_id = $this->activity_Type->where('activities_type.id', $id)
                ->update([
                    'is_active' => $request['is_active'],
                    'activity_id'=> $request['activity_id']
                ]);
            $request_activities = array_values($request->activity_type);
            foreach ($request_activities as $request_activitie) {
                $this->activity_typeTranslation->where('activity_type_id', $id)
                    ->where('local', $request_activitie['local'])
                    ->update([
                        'name' => $request_activitie ['name'],
                        'local' => $request_activitie['local'],
                        'activity_type_id' => $id,
                    ]);
            }
            DB::commit();
            return $this->returnData('activity_Type', [$id,$request_activities], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Delete activity_Type   ***
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        try {
//            Gate::authorize('Delete Brand');

            $activity_Type = $this->activity_Type->find($id);

            $activity_Type ->destroy($id);
            return $this->returnData('activity_Type', $activity_Type, 'This activity_Type Is deleted Now');


        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
}
