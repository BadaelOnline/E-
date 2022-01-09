<?php
namespace App\Service\CustomFields;

use App\Http\Requests\CustomField\CustomFieldRequest;
use App\Models\Custom_Fieldes\Custom_Field;
use App\Models\Custom_Fieldes\Custom_Field_Translation;
use App\Models\Custom_Fieldes\Custom_Field_Value;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class CustomFieldService
{
    use GeneralTrait;
    private $CustomFieldModel;
    private $Custom_Field_Translation;

    /**
     * Custom_field Service constructor.
     * @param Custom_Field $CustomFieldModel
     * @param Custom_Field_Translation $Custom_Field_Translation
     */
    public function __construct(Custom_Field $CustomFieldModel , Custom_Field_Translation $Custom_Field_Translation)
    {
        $this->CustomFieldModel=$CustomFieldModel;
        $this->Custom_Field_Translation=$Custom_Field_Translation;
    }
    /*___________________________________________________________________________*/
    /****Get All Active Custom_field Or By ID  ****/
    public function getAll()
    {
        try{
            Gate::authorize('Read Custom_field');
            $custom_field = $this->CustomFieldModel->with('Custom_Field_Value')->get();
            if (count($custom_field) > 0){
                return $this->returnData('Custom_fields',$custom_field,'done');
            }else{
                return $this->returnSuccessMessage('custom_field','custom_field doesnt exist yet');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    public function getById($id)
    {
        try{
            Gate::authorize('Read Custom_field');
            $custom_field =$this->CustomFieldModel->with('Custom_Field_Value')->find($id);
            if (is_null($custom_field) ){
                return $this->returnSuccessMessage('not found this Custom_field','done');
            }else{
                return $this->returnData('Custom_field',$custom_field,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    public function getCustomFieldsByProduct($id)
    {
        Gate::authorize('Read Custom_field');
        $custom_field=$this->CustomFieldModel->with('Product')->get();
        return $this->returnData('Custom_field',$custom_field,'done');
    }
    /*___________________________________________________________________________*/
    /****ــــــThis Functions For Trashed Custom_field  ****/
    /****Get All Trashed Products Or By ID  ****/
    public function getTrashed()
    {
        Gate::authorize('Read Custom_field');
        try{
            $custom_field = $this->CustomFieldModel->where('is_active',0)->get();
            return $this -> returnData('Custom_field',$custom_field,'done');
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****Restore Custom_field Fore Active status  ***
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreTrashed( $id)
    {
        try{
            Gate::authorize('Restore Custom_field');
            $custom_field=$this->CustomFieldModel->find($id);
            if (is_null($custom_field) ){
                return $response= $this->returnSuccessMessage('This Custom_field not found','done');
            }else{
                $custom_field->is_active=true;
                $custom_field->save();
                return $this->returnData('Custom_field', $custom_field,'This Custom_field Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****   Custom_field's Soft Delete   ***
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function trash($id)
    {
        try{
            Gate::authorize('Delete Custom_field');
            $custom_field=$this->CustomFieldModel->find($id);
            if (is_null($custom_field) ){
                return $response= $this->returnSuccessMessage('This Custom_field not found','done');
            }else{
                $custom_field->is_active=false;
                $custom_field->save();
                return $this->returnData('Custom_field', $custom_field,'This Custom_field Is trashed Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Create Custom_field   ***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /*___________________________________________________________________________*/
    public function create(CustomFieldRequest $request)
    {
        try
        {
            Gate::authorize('Create Custom_field');
            $request->validated();
            $request->is_active?$is_active=true:$is_active=false;
            //transformation to collection
            $allcustom_fields = collect($request->custom_field)->all();
            DB::beginTransaction();
            // //create the default language's custom_field
             $unTransCustomField_id=$this->CustomFieldModel->insertGetId([
                'is_active' =>$request['is_active'],
                'image' =>$request['image'],
            ]);
            //check the Custom_field and request
            if(isset($allcustom_fields) && count($allcustom_fields)) {
                //insert other traslations for custom field
                foreach ($allcustom_fields as $allCustomField) {
                    $transCustom_field_arr[] = [
                        'name' => $allCustomField ['name'],
                        'local' => $allCustomField['local'],
                        'description' => $allCustomField['description'],
                        'custom_field_id' => $unTransCustomField_id
                    ];
                }
                $this->Custom_Field_Translation->insert($transCustom_field_arr);

                if ($request->has('CustomFieldValues')) {
                    $CustomFields = $this->CustomFieldModel->find($unTransCustomField_id);
                    $customFieldValues = $request->CustomFieldValues;
                    foreach ($customFieldValues as $customFieldValue) {
                        //Custom Field Value
                        $cfv[] = [
                            'value' => $customFieldValue['value'],
                            'custom_field_id' => $unTransCustomField_id
                        ];
                    }
                    $CustomField = Custom_Field_Value::insert($cfv);
                }
            }
            DB::commit();
            return $this->returnData('customField', [$unTransCustomField_id,$transCustom_field_arr],'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError('customField',$ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Update Custom_field   ***
     * @param $id
     * @return Exception|\Illuminate\Http\JsonResponse
     */
    public function update(CustomFieldRequest $request,$id)
    {
        try{
            Gate::authorize('Update Custom_field');
            $request->validated();
            $custom_field= $this->CustomFieldModel->find($id);
            if(!$custom_field)
                return $this->returnError('400', 'not found this custom_field');
            if (!($request->has('custom_fields.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            DB::beginTransaction();
            $ncustom_field=$this->CustomFieldModel->where('custom_fields.id',$id)
                ->update([
                    'is_active' =>$request['is_active'],
//                    'image' =>$request['image']
                ]);
            $request_custom_fields = array_values($request->custom_field);
                foreach($request_custom_fields as $request_custom_field){
                    $this->Custom_Field_Translation->where('custom__fields__translations.custom_field_id',$id)
                        ->where('local',$request_custom_field['local'])
                        ->update([
                            'name' => $request_custom_field ['name'],
                            'local' => $request_custom_field['local'],
                            'custom_field_id' => $id,
                            'description' => $request_custom_field['description']
                        ]);
                }
            if ($request->has('CustomFieldValues')) {
                  $dbCustomFields = $custom_field->Custom_Field_Value()->get();
                $customFieldValues = $request->CustomFieldValues;
                 $collect=collect($dbCustomFields);
                foreach ($dbCustomFields as $dbCustomField) {
                    foreach ($customFieldValues as $customFieldValue) {
                        //Custom Field Value
                         $arr=[
                             'value'=> $customFieldValue['value'],
                             'custom_field_id'=> $id
                        ];
                    }
                }
                $dbCustomField->update($arr);
            }
            DB::commit();
            return $this->returnData('custom_field', [$id,$request_custom_fields],'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  ٍsearch for Custom_field   ***
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($name)
    {
        try {
            Gate::authorize('Read Custom_field');
            $custom_field = DB::table('custom_fields')
                ->where("name","like","%".$name."%")
                ->get();
            if (!$custom_field)
            {
                return $this->returnError('400', 'not found this Custom_field');
            }
            else
            {
                return $this->returnData('Custom_field', $custom_field,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /*___________________________________________________________________________*/
    /****  Delete Custom_field   ***
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        try{
            Gate::authorize('Delete Custom_field');
            $custom_field=$this->CustomFieldModel->find($id);
            if ($custom_field->is_active=0)
            {
                $custom_field=$this->CustomFieldModel->destroy($id);
                return $this->returnData( $custom_field,'This Custom_field Is deleted Now',200);
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    public function upload(Request $request)
    {
        $image = $request->file('image');
        $folder = public_path('images/customfields' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $request->image->move($folder,$filename);
        return $filename;
    }
    public function update_upload(Request $request,$id)
    {
        $custom_field= $this->CustomFieldModel->find($id);
        if (is_null($custom_field) ){
            return $this->returnSuccessMessage('not found this Custom_field','done');
        }
        $old_image=$custom_field->image;
        $image = $request->file('image');
        $old_images=public_path('images/customfields' . '/' . $old_image);
        if(File::exists($old_images)){
            unlink($old_images);
        }
        $folder = public_path('images/customfields' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $custom_field->update(['image' => $filename]);  /** update in database **/
        $image->move($folder,$filename);
        return $filename;
    }
}
