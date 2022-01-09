<?php

namespace App\Service\Admin;

use App\Models\Admin\Role;
use App\Models\Admin\TransModel\RoleTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleService
{

    use GeneralTrait;
    private $roleModel;
    private $roleTranslation;

    public function __construct(Role $role , RoleTranslation $roleTranslation)
    {
        $this->roleModel=$role;
        $this->roleTranslation=$roleTranslation;
    }
    /*___________________________________________________________________________*/
    /****Get All Active Role Or By ID  ****/
    public function getAll()
    {
        try{
            $role = $this->roleModel->get();
            if (count($role) > 0){
                return $response= $this->returnData('Role',$role,'done');
            }else{
                return $response= $this->returnSuccessMessage('Role','Role doesnt exist yet');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    public function getById($id)
    {
        try{
            $role =$this->roleModel->with('Permission')->find($id);
            if (is_null($role) ){
                return $response= $this->returnSuccessMessage('This Role not found','done');
            }else{
                return $response= $this->returnData('Role',$role,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****ــــــThis Functions For Trashed Role  ****/
    /****Get All Trashed Role Or By ID  ****/
    public function getTrashed()
    {
        try{
            $role = $this->roleModel->where('roles.is_active',0)->get();
            return $this -> returnData('Roles',$role,'done');
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****Restore Role Fore Active status  ***
     * @param $id
     */
    public function restoreTrashed( $id)
    {
        try{
            $role=$this->roleModel->find($id);
            if (is_null($role) ){
                return $response= $this->returnSuccessMessage('This Role not found','done');
            }else{
                $role->is_active=true;
                $role->save();
                return $this->returnData('Role', $role,'This Role Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****   Role's Soft Delete   ***
     * @param $id
     */
    public function trash( $id)
    {
        try{
            $role=$this->roleModel->find($id);
            if (is_null($role) ){
                return $response= $this->returnSuccessMessage('This Role not found','done');
            }else{
                $role->is_active=false;
                $role->save();
                return $this->returnData('Role', $role,'This Role Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Create category   ****/
    /*___________________________________________________________________________*/
    public function create(Request $request)
    {
        try {
//            $validated = $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            /****transformation to collection***/
            $allroles = collect($request->role)->all();
            DB::beginTransaction();
            /***create the default language's product***/
            $unTransRole_id = $this->roleModel->insertGetId([
                'slug' => $request['slug'],
                'is_active' => $request['is_active']
            ]);
            /***check the category and request**/
            if (isset($allroles) && count($allroles)) {
                /***insert other traslations for products**/
                foreach ($allroles as $allrole) {
                    $transRoles_arr[] = [
                        'name' => $allrole ['name'],
                        'display_name' => $allrole['display_name'],
                        'role_id' => $unTransRole_id,
                        'description' => $allrole['description'],
                        'local' => $allrole['local'],
                    ];
                }
                $this->roleTranslation->insert($transRoles_arr);
            }
            DB::commit();
            if ($request->has('permissions')) {
                $role = $this->roleModel->find($unTransRole_id);
                $role->Permission()->syncWithoutDetaching($request->get('permissions'));
            }
            return $this->returnData('Role', [$unTransRole_id],'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError('Role', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Update Role   ***/
    public function update(Request $request,$id)
    {
        try{
//            $validated = $request->validated();
            $role= $this->roleModel->find($id);
            if(!$role)
                return $this->returnError('400', 'not found this Role');
            $allroles = collect($request->role)->all();
            if (!($request->has('role.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);
            $nrole=$this->roleModel->where('roles.id',$id)
                ->update([
                    'slug'      =>$request['slug'],
                    'is_active' =>$request['is_active']
                ]);
            $ss=$this->roleTranslation->where('role_translation.role_id',$id);
            $collection1 = collect($allroles);
            $allroleslength=$collection1->count();
            $collection2 = collect($ss);

            $db_role= array_values(
                $this->roleTranslation
                    ->where('role_translation.role_id',$id)
                    ->get()
                    ->all());
            $dbdroles = array_values($db_role);
            $request_roles = array_values($request->role);
            foreach($dbdroles as $dbdrole){
                foreach($request_roles as $request_role){
                    $values= $this->roleTranslation->where('role_translation.role_id',$id)
                        ->where('local',$request_role['local'])
                        ->update([
                            'name'=>$request_role['name'],
                            'display_name'=>$request_role['display_name'],
                            'description'=>$request_role['description'],
                            'role_id'=>$id,
                            'local'=>$request_role['local']
                        ]);
                }
                return $this->returnData('Role', $dbdroles,'done');

            }
            if ($request->has('permissions')) {
                $role = $this->roleModel->find($id);
                $role->Permission()->syncWithoutDetaching($request->get('permissions'));
            }
            DB::commit();
        }
        catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  ٍsearch for role   ***
     */
    public function search($name)
    {
        try {
            $role = DB::table('roles')
                ->where("name","like","%".$name."%")
                ->get();
            if (!$role)
            {
                return $this->returnError('400', 'not found this Role');
            }
            else
            {
                return $this->returnData('Role', $role,'done');
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
            $role=$this->roleModel->find($id);
            if ($role->is_active=0)
            {
                $roles=$this->roleModel->destroy($id);
                return $this->returnData('Role', $roles,'This Role Is deleted Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400', $ex->getMessage());
        }
    }
}

