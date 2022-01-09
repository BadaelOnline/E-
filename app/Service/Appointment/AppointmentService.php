<?php


namespace App\Service\Appointment;


use App\Http\Requests\Appointment\AppointmentRequest;
use App\Models\Appointment\Appointment;
use App\Models\Appointment\AppointmentTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    private $AppointmentModel;
    use GeneralTrait;


    public function __construct(Appointment $appointment)
    {
        $this->AppointmentModel=$appointment;
    }
    //get all appointments
    public function get()
    {
        try
        {
          $appointment= $this->AppointmentModel::paginate(5);
        return $this->returnData('Appointment',$appointment,'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get appointment by appointment's id
    public function getById($id)
    {
        try
        {
        $appointment= $this->AppointmentModel::find($id);
            if (is_null($appointment) ){
        return $this->returnSuccessMessage('THis Appointment not found','done');
              }
            else{
            return  $this->returnData('appointment',$appointment,'done');
        }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//create a new appointment
    public function create( AppointmentRequest $request )
    {
        try {
            $allappointment = collect($request->appointment)->all();
            DB::beginTransaction();
            $unTransappointment_id = Appointment::insertGetId([
                'active_times_id' => $request['active_times_id'],
                'is_active' => $request['is_active'],
                'doctor_id' => $request['doctor_id'],
                'customer_id' => $request['customer_id'],
                'is_approved' => $request['is_approved'],
                'morning_evening' => $request['morning_evening'],

            ]);
            if (isset($allappointment)) {
                foreach ($allappointment as $allappointments) {
                    $transappointment[] = [
                        'description' => $allappointments ['description'],
                        'locale' => $allappointments['locale'],
                        'appointment_id' => $unTransappointment_id,
                    ];
                }
                AppointmentTranslation::insert($transappointment);
            }
            DB::commit();
            return $this->returnData('Appointment', [$unTransappointment_id, $transappointment], 'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
// update appointment
    public function update(AppointmentRequest $request,$id)
    {
        try{
            $appointment= Appointment::find($id);
            if(!$appointment)
                return $this->returnError('400', 'not found this appointment');
            $allappointment = collect($request->appointment)->all();
            if (!($request->has('appointments.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newappointment=Appointment::where('appointments.id',$id)
                ->update([
                    'active_times_id' => $request['active_times_id'],
                    'is_active' => $request['is_active'],
                    'doctor_id' => $request['doctor_id'],
                    'customer_id' => $request['customer_id'],
                    'is_approved' => $request['is_approved'],
                    'morning_evening' => $request['morning_evening'],
                ]);
            $ss=AppointmentTranslation::where('appointment_translations.appointment_id',$id);
            $collection1 = collect($allappointment);
            $allappointmentlength=$collection1->count();
            $collection2 = collect($ss);
            $db_appointment= array_values(AppointmentTranslation::where('appointment_translations.appointment_id',$id)
                ->get()
                ->all());
            $dbappointment = array_values($db_appointment);
            $request_appointment= array_values($request->appointment);
            foreach($dbappointment as $dbappointments){
                foreach($request_appointment as $request_appointments){
                    $values= AppointmentTranslation::where('appointment_id',$id)
                        ->where('locale',$request_appointments['locale'])
                        ->update([
                            'description' => $request_appointments ['description'],
                            'locale' => $request_appointments['locale'],
                            'appointment_id' => $id,
                        ]);
                }
            }
            DB::commit();
            return $this->returnData('Appointment', $dbappointment,'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//change the is_active value to zero
    public function trash( $id)
    {
        try {
            $appointment = $this->AppointmentModel::find($id);
            if (is_null($appointment)) {
                return $this->returnSuccessMessage('This Appointment not found', 'done');
            } else {
                $appointment->is_active =0;
                $appointment->save();
                return $this->returnData('Appointment', $appointment, 'This Appointment is trashed Now');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get appointment where is_active zero
    public function getTrashed()
    {
        try {
            $appointment= $this->AppointmentModel::NotActive();
            return $this -> returnData('Appointment',$appointment,'done');
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
        $appointment=Appointment::find($id);
            if (is_null($appointment)) {
                return $this->returnSuccessMessage('This Appointment not found', 'done');
            } else {
              $appointment->is_active=1;
              $appointment->save();
              return $this->returnData('Appointment', $appointment,'This Appointment is restore  trashed Now');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//delete appointment from database
    public function delete($id)
    {
        try {
        $appointment =Appointment::find($id);

            if ($appointment->is_active == 0) {
                $appointment->delete();
                $appointment->appointmenttranslation()->delete();

                return $this->returnData('appointment', $appointment, 'This appointment Is deleted Now');

            }
            else {
                return $this->returnData('appointment', $appointment, 'This appointment can not deleted Now');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }
}
