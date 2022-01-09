<?php


namespace App\Service\Specialty;


use App\Http\Requests\Specialty\SpecialtyRequest;
use App\Models\Doctors\doctor;
use App\Models\Doctors\DoctorTranslation;
use App\Models\medicalDevice\medicalDevice;
use App\Models\medicalDevice\MedicalDeviceTranslation;
use App\Models\Specialty\Specialty;
use App\Models\Specialty\SpecialtyTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class SpecialtyService
{
    private $SpecialtyModel;
    use GeneralTrait;


    public function __construct(Specialty $Specialty)
    {
        $this->SpecialtyModel=$Specialty;
    }
    //get all specialties
    public function get()
    {
        try {
            $Specialty = $this->SpecialtyModel::paginate(5);
            return $this->returnData(' Specialty', $Specialty, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get specialty by specialty  id
    public function getById($id)
    {
        try{
        $Specialty= $this->SpecialtyModel::find($id);
            if (is_null($Specialty)){
                return $this->returnSuccessMessage('this Social Media not found','done');
            }
            else{
                return $this->returnData('Specialty', $Specialty,'done');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//create new specialty
    public function create( SpecialtyRequest $request )
    {
        try {
            $allspecialty = collect($request->specialty)->all();
            DB::beginTransaction();
            $unTransspecialty_id = Specialty::insertGetId([
                'graduation_year' => $request['graduation_year'],
                'is_active' => $request['is_active'],
            ]);
            if (isset($allspecialty)) {
                foreach ($allspecialty as $allspecialties) {
                    $transspecialty[] = [
                        'name' => $allspecialties ['name'],
                        'description' => $allspecialties ['description'],
                        'locale' => $allspecialties['locale'],
                        'specialty_id' => $unTransspecialty_id,
                    ];
                }
               SpecialtyTranslation::insert($transspecialty);
            }
            DB::commit();
            return $this->returnData('Specialty', [$unTransspecialty_id, $transspecialty], 'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//update old specialty
    public function update(SpecialtyRequest $request,$id)
    {
        try{
            $specialty= Specialty::find($id);
            if(!$specialty)
                return $this->returnError('400', 'not found this Specialty');
            $allspecialty = collect($request->specialty)->all();
            if (!($request->has('specialties.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newspecialty=Specialty::where('specialties.id',$id)
                ->update([
                    'graduation_year' => $request['graduation_year'],
                    'is_active' => $request['is_active'],
                ]);
            $ss=SpecialtyTranslation::where('specialty_translation.specialty_id',$id);
            $collection1 = collect($allspecialty);
            $allspecialtylength=$collection1->count();
            $collection2 = collect($ss);
            $db_specialty= array_values(SpecialtyTranslation::where('specialty_translation.specialty_id',$id)
                ->get()
                ->all());
            $dbspecialty = array_values($db_specialty);
            $request_specialty= array_values($request->specialty);
            foreach($dbspecialty as $dbspecialties){
                foreach($request_specialty as $request_specialties){
                    $values= SpecialtyTranslation::where('specialty_translation.specialty_id',$id)
                        ->where('locale',$request_specialties['locale'])
                        ->update([
                            'name' => $request_specialties ['name'],
                            'description' => $request_specialties ['description'],
                            'locale' => $request_specialties['locale'],
                            'specialty_id' => $id,
                        ]);
                }
            }
            DB::commit();
            return $this->returnData('Specialty', [$dbspecialty,$values],'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
// search for a specialty by specialties name
    public function search($name)
    {
        try{
        $Specialty = DB::table('specialty_translation')
            ->where("name","like","%".$name."%")
            ->get();
        if (! $Specialty)
        {
            return $this->returnError('400', 'not found this Specialty');
        }
        else
        {
            return $this->returnData('Specialty',  $Specialty,'done');
        }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //change the is_active value to zero
    public function trash( $id)
    {
        try{
        $Specialty= $this->SpecialtyModel::find($id);
            if (is_null($Specialty)) {
                return $this->returnSuccessMessage('This Specialty not found', 'done');
            }
            else
            {
                $Specialty->is_active=0;
                $Specialty->save();
                return $this->returnData('Specialty',  $Specialty,'This Specialty is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get specialty where is_active value zero
    public function getTrashed()
    {
        try{
        $Specialty= $this->SpecialtyModel::NotActive();
        return $this -> returnData('Specialty', $Specialty,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//change the is_active value to one
    public function restoreTrashed( $id)
    {
        try{
        $Specialty=Specialty::find($id);
            if (is_null($Specialty)) {
                return $this->returnSuccessMessage('This Specialty not found', 'done');
            }
            else
            {
                $Specialty->is_active=1;
                $Specialty->save();
                return $this->returnData('Specialty',  $Specialty,'This Specialty is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //Permanently delete the specialty  from the database
    public function delete($id)
    {
        try{
        $Specialty = Specialty::find($id);
            if ($Specialty->is_active == 0) {
               $Specialty->delete();
               $Specialty->SpecialtyTranslation()->delete();
                return $this->returnData('Specialty',  $Specialty, 'This Specialty is deleted Now');
            }
            else{
                return $this->returnData('Specialty',  $Specialty, 'This Specialty can not deleted Now');
            }
    }
    catch (\Exception $ex) {
     return $this->returnError($ex->getCode(), $ex->getMessage());
     }
    }
//Knowing the doctor from the identifier of the specialty
    public function DoctorSpecialty($id)
    {
        try {
            $Specialty= Specialty::with('doctor')->find($id);
            return $this->returnData('Specialty',  $Specialty, 'done');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
