<?php


namespace App\Service\Doctors;

use App\Models\Doctors\Doctor;
use App\Models\User;
use App\Models\Admin\TransModel\UserTranslation;
use App\Models\Doctors\DoctorTranslation;
use App\Traits\GeneralTrait;
use App\Http\Requests\Doctors\DoctorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DoctorService
{
    private $DoctorModel;
    private $customerModel;
    use GeneralTrait;

    public function __construct(Doctor $doctor)
    {
        $this->DoctorModel = $doctor;
    }
        //get all doctors
    public function get()
    {
        try{
        $doctor= $this->DoctorModel::paginate(5);
        return $this->returnData('doctor',$doctor,'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get doctors details by doctor id
    public function getById($id)
    {
        try{
            $doctor=  Doctor::with('Specialty','clinic','medicalDevice','hospital')->find($id);
             return $this->returnData('doctor',$doctor,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//create new doctor
    public function create( DoctorRequest $request )
    {
        try {
            $alldoctor = collect($request->doctor)->all();
            DB::beginTransaction();
            $unTransdoctor_id =doctor::insertGetId([
                'clinic_id' => $request['clinic_id'],
                'user_id' => $request['user_id'],
                'is_active' => $request['is_active'],
                'is_approved' => $request['is_approved'],
            ]);
            if (isset($alldoctor)) {
                foreach ($alldoctor as $alldoctors) {
                    $transdoctor[] = [
                        'description' => $alldoctors ['description'],
                        'locale' => $alldoctors['locale'],
                        'doctor_id' => $unTransdoctor_id,
                    ];
                }
                DoctorTranslation::insert( $transdoctor);
            }
    
            DB::commit();
            return $this->returnData('doctor', [$unTransdoctor_id, $transdoctor], 'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//update doctor by doctor's id
    public function update(DoctorRequest $request,$id)
    {
        try{
            $doctor= Doctor::find($id);
            if(!$doctor)
                return $this->returnError('400', 'not found this doctor');
            $alldoctor = collect($request->doctor)->all();
            if (!($request->has('doctors.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newdoctor=Doctor::where('doctors.id',$id)
                ->update([
                    'clinic_id' => $request['clinic_id'],
                'user_id' => $request['user_id'],
                'is_active' => $request['is_active'],
                'is_approved' => $request['is_approved'],
                ]);

            $ss=DoctorTranslation::where('doctor_translation.doctor_id',$id);
            $collection1 = collect($alldoctor);
            $alldoctorlength=$collection1->count();
            $collection2 = collect($ss);

            $db_doctor= array_values(DoctorTranslation::where('doctor_translation.doctor_id',$id)
                ->get()
                ->all());
            $dbdoctor = array_values($db_doctor);
            $request_doctor= array_values($request->doctor);
            foreach($dbdoctor as $dbdoctors){
                foreach($request_doctor as $request_doctors){
                    $values= DoctorTranslation::where('doctor_translation.doctor_id',$id)
                        ->where('locale',$request_doctors['locale'])
                        ->update([
                            'description' => $request_doctors ['description'],
                            'locale' => $request_doctors['locale'],
                            'doctor_id' => $id,
                        ]);
                }
            }
            DB::commit();
            return $this->returnData('doctor', $dbdoctor,'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//Search for a doctor by his name
    public function search($name)
    {
        try {
            $doctor = DB::table('user_translation')
                ->where("first_name", "like", "%" . $name . "%")
                ->get();
            if (!$doctor) {
                return $this->returnError('400', 'not found this doctor');
            } else {
                return $this->returnData('doctor', $doctor, 'done');
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
         $doctor= $this->DoctorModel::find($id);
        if(is_null($doctor)){
            return $this->returnSuccessMessage('This doctor not found', 'done');}
        else{
        $doctor->is_active =0;
        $doctor->save();
        return $this->returnData('doctor', $doctor, 'This doctor is trashed Now');
    }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get doctor where is_active value zero
    public function getTrashed()
    {
        try {
            $doctor = $this->DoctorModel::NotActive();
            return $this->returnData('doctor', $doctor, 'done');
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
            $doctor = $this->DoctorModel::find($id);
            if (is_null($doctor)) {
                return $this->returnSuccessMessage('This doctor not found', 'done');
            } else {
                $doctor->is_active =1;
                $doctor->save();
                return $this->returnData('doctor', $doctor, 'This doctor is trashed Now');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    //Permanently delete the doctor from the database
    public function delete($id)
    {
        try{
        $doctor = $this->DoctorModel::find($id);
            if ($doctor->is_active == 0) {
                $doctor->delete();
                $doctor->doctortranslation()->delete();
                $doctor->User()->delete();
                return $this->returnData('doctor', $doctor, 'This doctor is deleted Now');
            }
            else {
                return $this->returnData('doctor', $doctor, 'This doctor can not deleted Now');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

//    get all doctor's social media by doctor's id
    public function DoctorSocialMedia($id)
    {
        try {
           $doctor= Doctor::with('socialMedia')->find($id);
            return $this->returnData('doctor', $doctor, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get  doctor's medical devices by doctor's id
    public function doctormedicaldevice($id)
    {
        try{
            $doctor= Doctor::with('medicalDevice')->find($id);
            return $this->returnData('doctor', $doctor, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    //get hospital by doctor's id
    public function doctorhospital($id)
    {
      try{
          $doctor= Doctor::with('hospital')->find($id);
          return $this->returnData('doctor', $doctor, 'done');
      }
      catch (\Exception $ex) {
           return $this->returnError($ex->getCode(), $ex->getMessage());
       }
    }

    //get doctor's appopintment by doctor's id
    public function doctorappointment($id)
    {
        try{
            $doctor= Doctor::with('appointment')->find($id);
            return $this->returnData('doctor', $doctor, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    //get doctor clinic by doctor's id
    public function doctorclinic($id)
    {
        try{
            $doctor= Doctor::with('clinic')->find($id);
            return $this->returnData('doctor', $doctor, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    //get patient by doctor's id
    public function Patient($id)
    {
        try{
            $doctor=  Doctor::with('Patient')->find($id);
            return $this->returnData('doctor', $doctor, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    //get doctor rate by doctor's id
    public function DoctorRate($id)
    {
        try{
            $doctor= Doctor::with('DoctorRate')->find($id);
            return $this->returnData('doctor', $doctor, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get doctor specialty by doctor's id
    public function DoctorSpecialty($id)
    {
        try {
            $doctor= Doctor::with('Specialty')->find($id);
            return $this->returnData('doctor', $doctor, 'done');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //___________________________________________________________________________________________//
 
    Public function InsertDoctorHospital(Request $request)
    {
        try{
        $doctor=Doctor::find($request->doctor_id);
        if(!$doctor)
        return $this->returnError('400','not found this doctor');

        $doctor->Hospital()->syncwithoutdetaching($request->hospital_id);
        return $this->returnData('doctor',$doctor,'create don');
        }
        catch(\Exception $ex){
            return $this->returnError($ex->getcode(),$ex->getMessage());
        }
    }

    public function InsertDoctorMedicalDevice(Request $request)
    {
        try{
        $doctor=Doctor::find($request->doctor_id);
        if(!$doctor)
        return $this->returnError('400','not found this doctor');
        $doctor->medicalDevice()->syncwithoutdetaching($request->medical_device_id);
        return $this->returnData('doctor',$doctor,'create done');
    }
    catch(\Exception $ex){
        return $this->returnError($ex->getcode(),$ex->getMessage());
    }
    }

    public function InsertDoctorSpecialty(Request $request)
    {
        try{
        $doctor=Doctor::find($request->doctor_id);
        if(!$doctor)
        return $this->returnError('400','not found this doctor');
        $doctor->Specialty()->syncwithoutdetaching($request->specialty_id);
        return $this->returnData('doctor',$doctor,'create done');
    }
    catch(\Exception $ex){
        return $this->returnError($ex->getcode(),$ex->getMessage());
    }
    }

    public function InsertDoctorPatient(Request $request)
    {
        try{
        $doctor=Doctor::find($request->doctor_id);
        if(!$doctor)
        return $this->returnError('400','not found this doctor');
        $doctor->Patient()->syncwithoutdetaching($request->patient_id);
        return $this->returnData('doctor',$doctor,'create done');
    }
    catch(\Exception $ex){
        return $this->returnError($ex->getcode(),$ex->getMessage());
    }
    }



}
