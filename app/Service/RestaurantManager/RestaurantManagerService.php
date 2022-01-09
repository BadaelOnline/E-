<?php

namespace App\Service\RestaurantManager;

use App\Http\Requests\RestaurantManager\RestaurantManagerRequest;
use App\Models\RestaurantManager\RestaurantManager;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class RestaurantManagerService
{
    private $RestaurantManagerModel;
    use GeneralTrait;

    public function __construct(RestaurantManager $manager)
    {
        $this->RestaurantManagerModel = $manager;
    }
    //get all Restaurant's Manager
    public function get()
    {
        try{
            $manager= $this->RestaurantManagerModel::paginate(5);
            return $this->returnData('Manager',$manager,'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get manager by manager id
    public function getById($id)
    {
        try{
            $manager= $this-> RestaurantManagerModel->find($id);
            if(!$manager)
                return  $this->returnError('400','this manager not found');
            else{
            return $this->returnData('Manager',$manager,'done');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//create new manager
//    public function create( RestaurantManagerRequest $request )
//    {
//        try {
//            $allmanager = collect($request->Manager)->all();
//            DB::beginTransaction();
//            $unTransmanager_id =RestaurantManager::insertGetId([
//                'user_id' => $request['user_id'],
//                'is_active' => $request['is_active'],
//                'is_approved' => $request['is_approved'],
//            ]);
//            if (isset($allmanager)) {
//                foreach ($allmanager as $allmanagers) {
//                    $transmanager[] = [
//                        'description' => $allmanagers ['description'],
//                        'locale' => $allmanagers['locale'],
//                        'manager_id' => $unTransmanager_id,
//                    ];
//                }
//                userTranslation::insert( $transmanager);
//            }
//            DB::commit();
//            return $this->returnData('Manager', [$unTransmanager_id, $transmanager], 'done');
//        }
//        catch(\Exception $ex)
//        {
//            DB::rollback();
//            return $this->returnError($ex->getCode(), $ex->getMessage());
//        }
//    }
////update manager by manager's id
//    public function update(RestaurantManagerRequest $request,$id)
//    {
//        try{
//            $manager= RestaurantManager::find($id);
//            if(!$manager)
//                return $this->returnError('400', 'not found this manager');
//            $allmanager = collect($request->Manager)->all();
//            if (!($request->has('restaurant_managers.is_active')))
//                $request->request->add(['is_active'=>0]);
//            else
//                $request->request->add(['is_active'=>1]);
//
//            $newmanager=RestaurantManager::where('restaurant_managers.id',$id)
//                ->update([
//                    'user_id' => $request['user_id'],
//                    'is_active' => $request['is_active'],
//                    'is_approved' => $request['is_approved'],
//                ]);
//
//            $ss=userTranslation::where('user_translation.user_id',$id);
//            $collection1 = collect($allmanager);
//            $allmanagerlength=$collection1->count();
//            $collection2 = collect($ss);
//
//            $db_manager= array_values(userTranslation::where('user_translation.user_id',$id)
//                ->get()
//                ->all());
//            $dbmanager = array_values($db_manager);
//            $request_manager= array_values($request->Manager);
//            foreach($dbmanager as $dbmanagers){
//                foreach($request_manager as $request_managers){
//                    $values= userTranslation::where('user_translation.user_id',$id)
//                        ->where('locale',$request_managers['locale'])
//                        ->update([
//                            'description' => $request_managers ['description'],
//                            'locale' => $request_managers['locale'],
//                            'user_id' => $id,
//                        ]);
//                }
//            }
//            DB::commit();
//            return $this->returnData('Manager', $dbmanager,'done');
//        }
//        catch(\Exception $ex){
//            return $this->returnError($ex->getCode(), $ex->getMessage());
//        }
//    }
//Search for a manager by his name
    public function search($name)
    {
        try {
            $manager = DB::table('users')
                ->where("first_name", "like", "%" . $name . "%")
                ->get();
            if (!$manager) {
                return $this->returnError('400', 'not found this Manager');
            } else {
                return $this->returnData('Manager', $manager, 'done');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//Change the is_active value to zero
    public function trash( $id)
    {
        try{
            $manager= $this->RestaurantManagerModel::find($id);
            if(is_null($manager)){
                return $this->returnSuccessMessage('This manager not found', 'done');}
            else{
               $manager->is_active =0;
               $manager->save();
                return $this->returnData('Manager', $manager, 'This Manager is trashed Now');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get manager where is_active value zero
    public function getTrashed()
    {
        try {
            $manager = $this->RestaurantManagerModel::NotActive();
            return $this->returnData('Manager', $manager, 'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//Change the is_active value to one
    public function restoreTrashed( $id)
    {
        try {
            $manager = $this->RestaurantManagerModel::find($id);
            if (is_null($manager)) {
                return $this->returnSuccessMessage('This Manager not found', 'done');
            } else {
                $manager->is_active =1;
                $manager->save();
                return $this->returnData('Manager', $manager, 'This Manager is trashed Now');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    //Permanently delete the manager from the database
    public function delete($id)
    {
        try{
            $manager = $this->RestaurantManagerModel::find($id);
            if ($manager->is_active == 0) {
                $manager->delete();
                $manager->user()->delete();
                return $this->returnData('Manager', $manager, 'This Manager is deleted Now');
            }
            else {
                return $this->returnData('Manager', $manager, 'This Manager can not deleted Now');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
