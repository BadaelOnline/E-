<?php


namespace App\Service\Hospital;


use App\Http\Requests\Hospital\HospitalRequest;
use App\Models\Hospital\Hospital;
use App\Models\Hospital\HospitalTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class HospitalService
{
    private $HospitalModel;
    use GeneralTrait;

    public function __construct(Hospital $Hospital)
    {
        $this->HospitalModel=$Hospital;
    }
    //get all hospitals
    public function get()
    {
        try {
            $Hospital = $this->HospitalModel::paginate(5);
            return $this->returnData('Hospital', $Hospital, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get hospital by hospitals id
    public function getById($id)
    {
        try{
        $Hospital= $this->HospitalModel::find($id);
        if (is_null($Hospital)){
            return $this->returnSuccessMessage('this Hospital not found','done');
        }
        else{
            return $this->returnData('Hospital',$Hospital,'done');
        }
    }
    catch(\Exception $ex)
      {
         return $this->returnError($ex->getCode(),$ex->getMessage());
      }
    }
    //create new hospital
    public function create( HospitalRequest $request )
    {
        try {
            $allhospital = collect($request->hospital)->all();
            DB::beginTransaction();
            $unTranshospital_id = Hospital::insertGetId([
                'general_hospital'   => $request['general_hospital'],
                'private_hospital' => $request['private_hospital'],
                'is_approved' => $request['is_approved'],
                'is_active'   => $request['is_active'],
                'location_id'=>$request['location_id'],
            ]);
            if (isset($allhospital)) {
                foreach ($allhospital as $allhospitals) {
                    $transhospital[] = [
                        'name' => $allhospitals ['name'],
                        'description' => $allhospitals ['description'],
                        'locale' => $allhospitals['locale'],
                        'hospital_id' => $unTranshospital_id,
                    ];
                }
                HospitalTranslation::insert($transhospital);
            }
            DB::commit();
            return $this->returnData('Hospital',[$unTranshospital_id, $transhospital],'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }
    //update old hospital
    public function update(HospitalRequest $request,$id)
    {
        try{
            $hospital= Hospital::find($id);
            if(!$hospital)
                return $this->returnError('400', 'not found this hospital');
            $allhospital = collect($request->hospital)->all();
            if (!($request->has('hospitals.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newhospital=Hospital::where('hospitals.id',$id)
                ->update([
                    'general_hospital'   => $request['general_hospital'],
                    'private_hospital' => $request['private_hospital'],
                    'is_approved' => $request['is_approved'],
                    'is_active'   => $request['is_active'],
                    'location_id'=>$request['location_id'],
                ]);

            $ss=HospitalTranslation::where('hospital_translations.hospital_id',$id);
            $collection1 = collect($allhospital);
            $alldoctorlength=$collection1->count();
            $collection2 = collect($ss);

            $db_hospital= array_values(HospitalTranslation::where('hospital_translations.hospital_id',$id)
                ->get()
                ->all());
            $dbhospital = array_values($db_hospital);
            $request_hospital= array_values($request->hospital);
            foreach($dbhospital as $dbhospitals){
                foreach($request_hospital as $request_hospitals){
                    $values=HospitalTranslation::where('hospital_translations.hospital_id',$id)
                        ->where('locale',$request_hospitals['locale'])
                        ->update([
                            'name' => $request_hospitals ['name'],
                            'description' => $request_hospitals ['description'],
                            'locale' => $request_hospitals['locale'],
                            'hospital_id' => $id,
                        ]);
                }
            }
            DB::commit();
            return $this->returnData(' hospital', [$db_hospital,$values],'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //search for a hospital by name
    public function search($name)
    {
        try {
            $Hospital = DB::table('hospital_translations')
                ->where("name", "like", "%" . $name . "%")
                ->get();
            if (!$Hospital) {
                return $this->returnError('400', 'not found this hospital');
            } else {
                return $this->returnData('Hospital', $Hospital, 'done');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //Change the is_active value to zero
    public function trash( $id)
    {
        try{
        $Hospital= $this->HospitalModel::find($id);
            if (is_null($Hospital)) {
                return $this->returnSuccessMessage('This Hospital not found', 'done');
            }
            else
            {
                $Hospital->is_active=0;
                $Hospital->save();
                return $this->returnData('Hospital', $Hospital,'This Hospital is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }
    //get hospital where is_active value zero
    public function getTrashed()
    {
        try{
            $Hospital= $this->HospitalModel::NotActive();
            return $this -> returnData('Hospital',$Hospital,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //Change the is_active value to one
    public function restoreTrashed( $id)
    {
        try{
        $Hospital=Hospital::find($id);
            if (is_null($Hospital)) {
                return $this->returnSuccessMessage('This Hospital not found', 'done');
            }
            else
            {
                $Hospital->is_active=1;
                $Hospital->save();
                return $this->returnData('Hospital', $Hospital,'This Hospital is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //Permanently delete the hospital from the database
    public function delete($id)
    {
        try{
        $Hospital = Hospital::find($id);
            if ($Hospital->is_active == 0) {

                $Hospital->delete();
                $Hospital->hospitalTranslation()->delete();
            return $this->returnData('Hospital', $Hospital, 'This Hospital is deleted Now');
            }
            else{
                return $this->returnData('Hospital', $Hospital, 'This Hospital can not deleted Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get all the doctors who work in the hospital according to her name
    public function hospitalsDoctor($id)
    {
        try{
            $hospital=  Hospital::with('doctor')->find($id);
            return $this->returnData('Hospital',  $hospital, 'done');

        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

}
