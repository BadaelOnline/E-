<?php

namespace App\Service\Admin;

use App\Models\Admin\Role;
use App\Models\Admin\TransModel\TypeUserTranslation;
use App\Models\Admin\TypeUser;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class TypeUserService
{

    use GeneralTrait;
    private $typeModel;
    private $roleModel;
    private $typeTranslation;

    public function __construct(TypeUser $typeModel ,
                                Role $roleModel ,
                                TypeUserTranslation $typeTranslation)
    {
        $this->typeModel=$typeModel;
        $this->roleModel=$roleModel;
        $this->typeTranslation=$typeTranslation;
    }
    /*___________________________________________________________________________*/
    /****  Get All Active type Or By ID  ****/
    public function getAll()
    {
        try{
            $type = $this->typeModel->get();
            if (count($type) > 0){
                return $response= $this->returnData('type',$type,'done');
            }else{
                return $response= $this->returnSuccessMessage('type','type doesnt exist yet');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    public function getById($id)
    {
        try{
            $type =$this->typeModel->with(['roles'=>function($q){
                return $q->with('Permission')->get();},'User'])->find($id);
            if (is_null($type) ){
                return $response= $this->returnSuccessMessage('This type not found','done');
            }else{
                return $response= $this->returnData('type',$type,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  ـThis Functions For Trashed type   ****/
    /****Get All Trashed type Or By ID  ****/
    public function getTrashed()
    {
        try{
            $type = $this->typeModel->where('type_users.is_active',0)->get();
            if (is_null($type) ){
                return $response= $this->returnSuccessMessage('This type not found','done');
            }else{
                return $response= $this->returnData('type',$type,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Restore type Fore Active status  ***
     * @param $id
     */
    public function restoreTrashed( $id)
    {
        try{
            $type=$this->typeModel->find($id);
            if (is_null($type) ){
                return $response= $this->returnSuccessMessage('This type not found','done');
            }else{
                $type->is_active=true;
                $type->save();
                return $this->returnData('type', $type,'This type Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****   type's Soft Delete   ***
     * @param $id
     */
    public function trash( $id)
    {
        try{
            $type=$this->typeModel->find($id);
            if (is_null($type) ){
                return $response= $this->returnSuccessMessage('This type not found','done');
            }else{
                $type->is_active=false;
                $type->save();
                return $this->returnData('type', $type,'This type Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Create type   ****/
    /*___________________________________________________________________________*/
    public function create(Request $request)
    {
        try {
//            $validated = $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            DB::beginTransaction();
            $alltypes = collect($request->type)->all();

            $type=$this->typeModel->create([
               'is_active' => $request->is_active
            ]);
            $typeid=$type->id;
            if (isset($alltypes) && count($alltypes)) {
                //insert other traslations for users
                foreach ($alltypes as $alltype) {
                    $transtype_arr[] = [
                        'name' => $alltype ['name'],
                        'description' => $alltype ['description'],
                        'local' => $alltype['local'],
                        'type_users_id' => $typeid
                    ];
                }
                $this->typeTranslation->insert($transtype_arr);
            }
            if ($request->has('roles')) {
                $role = $this->typeModel->find($typeid);
                $role->roles()->syncWithoutDetaching($request->get('roles'));
            }
            if ($request->has('permissions')) {
                $permissions = $this->typeModel->find($type->id);
                $permissions->permissions()->syncWithoutDetaching($request->get('permissions'));
            }
            DB::commit();
            return $this->returnData('type',$type,'Done');

        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Update Role   ***/
    public function update(Request $request,$id)
    {
        try{
//            $validated = $request->validated();
            $type=$this->typeModel->find($id);
            if(!$type)
                return $this->returnError('400', 'not found this type');
            $alltypes = collect($request->type)->all();
            if (!($request->has('type_users.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);
            $ntype=$this->typeModel->where('type_users.id',$id)
                ->update([
                'age' => $request->age,
                'location_id' => $request->location_id,
                'social_media_id' => $request->social_media_id,
                'is_active' => $request->is_active,
                'image' => $request->image,
                'password' =>bcrypt($request->password)
            ]);

            $ss=$this->typeTranslation->where('type_users_translation.type_users_id',$id);
            $collection1 = collect($alltypes);
            $alltypeslength=$collection1->count();
            $collection2 = collect($ss);

            $db_type= array_values(
                $this->typeTranslation
                    ->where('type_users_translation.type_users_id',$id)
                    ->get()
                    ->all());
            $dbdtypes = array_values($db_type);
            $request_types = array_values($request->type);
            foreach($dbdtypes as $dbdtype){
                foreach($request_types as $request_type){
                    $values= $this->typeTranslation->where('type_users_translation.type_users_id',$id)
                        ->where('local',$request_type['local'])
                        ->update([
                            'first_name' => $request_type ['first_name'],
                            'last_name' => $request_type ['last_name'],
                            'local' => $request_type['local'],
                            'employee_id' => $id
                        ]);
                    }
            }
            $token = JWTAuth::fromUser($type);
            if ($request->has('roles')) {
                $role = $this->typeModel->find($type->id);
                $role->roles()->syncWithoutDetaching($request->get('roles'));
            }
            if ($request->has('permissions')) {
                $permissions = $this->typeModel->find($type->id);
                $permissions->permissions()->syncWithoutDetaching($request->get('permissions'));
            }
            DB::commit();
            return $this->returnData('type', [$token,$type],'Done');

        }
        catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Search For type  ****/
    public function search($name)
    {
        try {
            $type = DB::table('user_types')
                ->where("name","like","%".$name."%")
                ->get();
            if (!$type)
            {
                return $this->returnError('400', 'not found this type');
            }
            else
            {
                return $this->returnData('type', $type,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Delete type   ***/
    public function delete($id)
    {
        try{
            $type=$this->typeModel->find($id);
            if ($type->is_active=0)
            {
                $roles=$this->typeModel->destroy($id);
                return $this->returnData('type', $type,'This type Is deleted Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  type Profile   ***/
    public function profile($id)
    {
        try{
            $type=$this->typeModel->with([
                'roles'=>function($q){
                return $q->with('Permission')->get();
                },
                'Stores_Order'
            ])->find($id);
            if (is_null($type) ){
                return $response= $this->returnSuccessMessage('This type not found','done');
            }else{
                return $response= $this->returnData('type',$type,'done');
            }
        }
        catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
}
