<?php

namespace App\Service\Patient;

use App\Http\Requests\Patient\PatientRequest;
use App\Models\Doctors\Patient;
use App\Traits\GeneralTrait;

class PatientService
{
    private $PatientModel;
    use GeneralTrait;


    public function __construct(Patient $patient)
    {
        $this->PatientModel=$patient;
    }
    //get all patient
    public function getAll()
    {
        try {
            $patient = $this->PatientModel::IsActive();
            return $this->returnData('Patient', $patient , 'done');
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
        }
    public function getById($id)
    {
        try{
            $patient= $this->PatientModel->find($id);
            if (is_null($patient)){
                return $this->returnSuccessMessage('this patient not found','done');
            }
            else {
                return $this->returnData('patient', $patient, 'done');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function create(PatientRequest $request)
    {
        try {
            $patient=new Patient();

            $patient->medical_file_number     =$request->medical_file_number;
            $patient->medical_file_date     =$request->medical_file_date;
            $patient->review_date         =$request->review_date;
            $patient->pdf                 =$request->pdf;
            $patient->gender              =$request->gender;
            $patient->social_status       =$request->social_status;
            $patient->note                =$request->note;
            $patient->blood_type          =$request->blood_type;
            $patient->is_active           =$request->is_active;
            $patient->is_approved         =$request->is_approved;

            $result= $patient->save();

            if ($result)
            {
                return $this->returnData('Patient', $patient,'done');
            }
            else
            {
                return $this->returnError('400', 'saving failed');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    //__________________________________________________________
    public function update(PatientRequest $request,$id)
    {
        try {
            $patient=$this->PatientModel->find($id);

            $patient->medical_file_number =$request->medical_file_number;
            $patient->medical_file_date   =$request->medical_file_date;
            $patient->review_date         =$request->review_date;
            $patient->pdf                 =$request->pdf;
            $patient->gender              =$request->gender;
            $patient->social_status       =$request->social_status;
            $patient->note                =$request->note;
            $patient->blood_type          =$request->blood_type;
            $patient->is_active           =$request->is_active;
            $patient->is_approved         =$request->is_approved;

            $result= $patient->save();

            if ($result)
            {
                return $this->returnData('Patient', $patient,'done');
            }
            else
            {
                return $this->returnError('400', 'updating failed');
            }        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function trash( $id)
    {
        try{
            $patient= $this->PatientModel::find($id);
            if(is_null($patient)){
                return $this->returnSuccessMessage('This patient not found', 'done');}
            else{
                $patient->is_active =0;
                $patient->save();
                return $this->returnData('patient', $patient, 'This patient is trashed Now');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function getTrashed()
    {
        try {
            $patient = $this->PatientModel::NotActive();
            return $this->returnData('patient', $patient, 'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function restoreTrashed( $id)
    {
        try {
            $patient = $this->PatientModel::find($id);
            if (is_null($patient)) {
                return $this->returnSuccessMessage('This patient not found', 'done');
            } else {
                $patient->is_active =1;
                $patient->save();
                return $this->returnData('patient', $patient, 'This patient is restore trashed Now');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function delete($id)
    {
        try{
            $patient = $this->PatientModel::find($id);
            if ($patient->is_active == 0) {
                $patient->delete();
                return $this->returnData('patient', $patient, 'This patient is deleted Now');

            }
            else {
                return $this->returnData('patient', $patient, 'This patient can not deleted Now');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }

    }
}
