<?php


namespace App\Service\Admin;


use App\Models\Admin\Permission;
use App\Models\Admin\TransModel\PermissionTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class permissionService
{
    use GeneralTrait;
    private $permissionModel;
    private $permissionTranslation;

    public function __construct(Permission $permission , PermissionTranslation $permissionTranslation)
    {
        $this->permissionModel=$permission;
        $this->permissionTranslation=$permissionTranslation;
    }
    /*___________________________________________________________________________*/
    /****Get All Active Permission Or By$permission ID  ****/
    public function getAll()
    {
        try{
            $permissions = $this->permissionModel->get();
            if (count($permissions) > 0){
                return $response= $this->returnData('Permissions',$permissions,'done');
            }else{
                return $response= $this->returnSuccessMessage('Permissions','Permissions doesnt exist yet');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    public function getById($id)
    {
        try{
            $permission =$this->permissionModel->find($id);
            if (is_null($permission) ){
                return $response= $this->returnSuccessMessage('This Permission not found','done');
            }else{
                return $response= $this->returnData('Permission',$permission,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    public function getCategoryBySelf($id)
    {
        $permission=$this->permissionModel->get();
        return $response= $this->returnData('Permissions',$permission,'done');
    }
    /*___________________________________________________________________________*/
    /****ــــــThis Functions For Trashed Permission  ****/
    /****Get All Trashed Permission Or By ID  ****/
    public function getTrashed()
    {
        try{
            $permission = $this->permissionModel->where('permissions.is_active',0)->get();
            return $this -> returnData('Permissions',$permission,'done');
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****Restore Permission Fore Active status  ***
     * @param $id
     */
    public function restoreTrashed( $id)
    {
        try{
            $permission=$this->permissionModel->find($id);
            if (is_null($permission) ){
                return $response= $this->returnSuccessMessage('This Permission not found','done');
            }else{
                $permission->is_active=true;
                $permission->save();
                return $this->returnData('Permission', $permission,'This Permission Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****   Permission's Soft Delete   ***
     * @param $id
     */
    public function trash( $id)
    {
        try{
            $permission=$this->permissionModel->find($id);
            if (is_null($permission) ){
                return $response= $this->returnSuccessMessage('This Permission not found','done');
            }else{
                $permission->is_active=false;
                $permission->save();
                return $this->returnData('Permission', $permission,'This Permission Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Create Permission   ****/
    /*___________________________________________________________________________*/
    public function create(Request $request)
    {
        try {
//            $validated = $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            //transformation to collection
            $allpermissions = collect($request->permission)->all();

            DB::beginTransaction();
            // //create the default language's product
            $unTransPermission_id = $this->permissionModel->insertGetId([
                'slug' => $request['slug'],
                'is_active' => $request['is_active']
            ]);
            //check the Permissions and request
            if (isset($allpermissions) && count($allpermissions)) {
                //insert other traslations for Permisssion
                foreach ($allpermissions as $allpermission) {
                    $transpermission_arr[] = [
                        'local' => $allpermission ['local'],
                        'name' => $allpermission ['name'],
                        'display_name' => $allpermission['display_name'],
                        'permission_id' => $unTransPermission_id,
                        'description' => $allpermission['description']
                    ];
                }
                $this->permissionTranslation->insert($transpermission_arr);
            }

            DB::commit();
            return $this->returnData('Permission', [$unTransPermission_id,$transpermission_arr],'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError('Permission', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Update Role   ***/
    public function update(Request $request,$id)
    {
        try{
//            $validated = $request->validated();
            $permission= $this->permissionModel->find($id);
            if(!$permission)
                return $this->returnError('400', 'not found this Permission');
            $allpermissions = collect($request->permission)->all();
            if (!($request->has('permissions.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);
            $npermission=$this->permissionModel->where('permissions.id',$id)
                ->update([
                    'slug'      =>$request['slug'],
                    'is_active' =>$request['is_active']
                ]);
            $ss=$this->permissionTranslation->where('permission_translation.permission_id',$id);
            $collection1 = collect($allpermissions);
            $allpermissionslength=$collection1->count();
            $collection2 = collect($ss);

            $db_permission= array_values(
                $this->permissionTranslation
                    ->where('permission_translation.permission_id',$id)
                    ->get()
                    ->all());
            $dbdpermissions = array_values($db_permission);
            $request_permissions = array_values($request->permission);
            foreach($dbdpermissions as $dbdpermission){
                foreach($request_permissions as $request_permission){
                    $values= $this->permissionTranslation->where('permission_translation.permission_id',$id)
                        ->where('local',$request_permission['local'])
                        ->update([
                            'local' => $request_permission ['local'],
                            'name'=>$request_permission['name'],
                            'display_name'=>$request_permission['display_name'],
                            'description'=>$request_permission['description'],
                            'permission_id'=>$id
                        ]);
                }
                return $this->returnData('Permission', $dbdpermissions,'done');

            }
            DB::commit();
        }
        catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  ٍsearch for Permission   ***
     */
    public function search($name)
    {
        try {
            $permission = DB::table('permission_translation')
                ->where("name","like","%".$name."%")
                ->get();
            if (!$permission)
            {
                return $this->returnError('400', 'not found this Permission');
            }
            else
            {
                return $this->returnData('Permission', $permission,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Delete category   ***/

    public function delete($id)
    {
        try{
            $role=$this->permissionModel->find($id);
            if ($role->is_active=0)
            {
                $roles=$this->permissionModel->destroy($id);
                return $this->returnData('Permission', $roles,'This Permission Is deleted Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
}
