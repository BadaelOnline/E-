<?php


namespace App\Service\DoctorRate;

use App\Http\Requests\DoctorRate\DoctorRateRequest;
use App\Models\DoctorRate\DoctorRate;
use App\Models\Doctors\doctor;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class DoctorRateService
{
    private $DoctorRateModel;
    use GeneralTrait;

    public function __construct(DoctorRate $doctorRate)
    {
        $this->DoctorRateModel=$doctorRate;
    }
//Get all rates of all doctors
    public function get()
    {
        try{
        $doctorRate=$this->DoctorRateModel::all();
        return $this->returnData('DoctorRate',$doctorRate,'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get doctor's rate by rate id

    public function getById($id)
    {
      try{
        $doctorRate= $this->DoctorRateModel::find($id);
          if (is_null($doctorRate)){
              return $this->returnSuccessMessage('this Doctor Rate not found','done');
          }
          else{
              return  $this->returnData('doctorRate',$doctorRate,'done');
          }
      }
      catch(\Exception $ex)
      {
          return $this->returnError($ex->getCode(),$ex->getMessage());
      }
    }
//create new doctor's rate

    public function create( DoctorRateRequest $request )
    {
        try {
            $doctorRate = new DoctorRate();
            $doctorRate->doctor_id = $request->doctor_id;
            $doctorRate->rate = $request->rate;
            $doctorRate->is_active = $request->is_active;

            $result = $doctorRate->save();
            if ($result) {
                return $this->returnData('doctorRate', $doctorRate, 'done');
            } else {
                return $this->returnError('400', 'saving failed');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400', $ex->getMessage());
        }
    }
//update doctor's rate by rate id
    public function update(DoctorRateRequest $request,$id)
    {
      try{
               $doctorRate= $this->DoctorRateModel::find($id);
               $doctorRate->doctor_id                =$request->doctor_id;
               $doctorRate->rate                     =$request->rate;
               $doctorRate->is_active = $request->is_active;


          $result=$doctorRate->save();
          if ($result)
          {
              return $this->returnData('doctorRate', $doctorRate,'done');
          }
          else
          {
              return $this->returnError('400', 'updating failed');
          }
    }
    catch(\Exception $ex)
        {
           return $this->returnError($ex->getCode(), $ex->getMessage());
         }

    }
//Change the is_active value to zero
    public function trash( $id)
    {
        try{
        $doctorRate= $this->DoctorRateModel::find($id);
            if (is_null($doctorRate)) {
                return $this->returnSuccessMessage('This doctorRate not found', 'done');
            }
            else
            {
              $doctorRate->is_active=0;
              $doctorRate->save();
              return $this->returnData('DoctorRate', $doctorRate,'This DoctorRate is trashed Now');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get doctor's rate where is_active value zero
    public function getTrashed()
    {
        try {
            $doctorRate = $this->DoctorRateModel::all()->where('is_active', 0);
            return $this->returnData('brand', $doctorRate, 'done');
        }
        catch(\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
////Change the is_active value to one
    public function restoreTrashed( $id)
    {
        try {
            $doctorRate = DoctorRate::find($id);
            if (is_null($doctorRate)) {
                return $this->returnSuccessMessage('This doctorRate not found', 'done');
            } else {
                $doctorRate->is_active = 1;
                $doctorRate->save();
                return $this->returnData('DoctorRate', $doctorRate, 'This DoctorRate is trashed Now');
            }
        }
            catch(\Exception $ex)
            {
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    //Permanently delete the doctor's rate from the database
    public function delete($id)
    {
        try {
            $doctorRate = DoctorRate::find($id);
            if ($doctorRate->is_active == 0) {

                $doctorRate->delete();
                return $this->returnData('DoctorRate', $doctorRate, 'This Doctor Rate is deleted Now');
            }
            else
            {
                return $this->returnData('DoctorRate', $doctorRate, 'This Doctor Rate can not deleted Now');

            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

}
