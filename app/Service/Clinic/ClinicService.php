<?php


namespace App\Service\Clinic;


use App\Http\Requests\Clinic\ClinicRequest;
use App\Models\Clinic\Clinic;
use App\Models\Clinic\ClinicTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class ClinicService
{
    private $ClinicModel;
    use GeneralTrait;

    public function __construct(Clinic $clinic)
    {
        $this->ClinicModel=$clinic;
    }
    //get all clinics
    public function get()
    {
        try
        {
        $clinic= $this->ClinicModel->paginate(5);
        return $this->returnData('clinic',$clinic,'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get clinic by clinic id
    public function getById($id)
    {
        try
        {
        $clinic= $this->ClinicModel->find($id);
        if (is_null($clinic)){
        return $this->returnSuccessMessage('this clinic not found','done');
        }
        else{
            return  $this->returnData('clinic',$clinic,'done');
        }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }

    }
// create a new clinic
    public function create( ClinicRequest $request )
    {
        try {
            $clinic = new Clinic();

            $clinic->  location_id = $request->location_id;
            $clinic-> doctor_id  = $request->doctor_id;
            $clinic->is_approved  = $request->is_approved;
            $clinic->is_active    = $request->is_active;
            $clinic->phone_number  = $request->phone_number;
            $clinic->active_times_id    = $request->active_times_id;
            $clinic->name            = $request->name;

            $result = $clinic->save();

            if ($result)
            {
                return $this->returnData('Clinic', $clinic, 'done');
            }
            else
            {
                return $this->returnError('400', 'saving failed ');
            }
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//update old clinic
    public function update(ClinicRequest $request,$id)
    {
        try{
            $clinic=Clinic::find($id);

            $clinic->location_id = $request->location_id;
            $clinic-> doctor_id  = $request->doctor_id;
            $clinic->is_approved  = $request->is_approved;
            $clinic->is_active    = $request->is_active;
            $clinic->phone_number  = $request->phone_number;
            $clinic->active_times_id    = $request->active_times_id;
            $clinic->name            = $request->name;

            $result = $clinic->save();

            if ($result)
            {
                return $this->returnData('Clinic', $clinic, 'done');
            }
            else
            {
                return $this->returnError('400', 'saving failed ');
            }
        }
        catch(\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//search of clinic by clinic name
    public function search($name)
    {
        try {
            $clinic = DB::table('clinics')
                ->where("name", "like", "%" . $name . "%")
                ->get();
            if (!$clinic) {
                return $this->returnError('400', 'not found this doctor');
            } else {
                return $this->returnData('clinic', $clinic, 'done');
            }
        }
        catch(\Exception $ex){
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    //change  the is_active value to zero
    public function trash( $id)
    {
        try {
        $clinic= $this->ClinicModel::find($id);
            if (is_null($clinic)) {
                return $this->returnSuccessMessage('This Appointment not found', 'done');
            }
           else
            {
                $clinic->is_active=0;
                $clinic->save();
                return $this->returnData('clinic', $clinic,'This clinic is trashed Now');
            }
         }
       catch (\Exception $ex)
       {
         return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get doctor where is_active value zero
    public function getTrashed()
    {
        try {
              $clinic= $this->ClinicModel->NotActive()->get();
              return $this -> returnData('clinic',$clinic,'done');
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //change the is_active value to one
    public function restoreTrashed( $id)
    {
        try {
             $clinic=Clinic::find($id);
            if (is_null($clinic)) {
                return $this->returnSuccessMessage('This clinic not found', 'done');
            }
        else
        {
             $clinic->is_active=1;
             $clinic->save();
              return $this->returnData('clinic', $clinic,'This clinic is trashed Now');
         }
        }
        catch (\Exception $ex)
      {
        return $this->returnError($ex->getCode(), $ex->getMessage());
      }
    }
    //Permanently delete the clinic from the database
    public function delete($id)
    {
        try {
            $clinic = Clinic::find($id);
            if ($clinic->is_active == 0) {
                $clinic->delete();
                return $this->returnData('clinic', $clinic, 'This clinic Is deleted Now');
            }
            else {
                return $this->returnData('clinic', $clinic, 'This clinic can not deleted Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

}
