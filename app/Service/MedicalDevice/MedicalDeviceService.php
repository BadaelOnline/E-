<?php


namespace App\Service\MedicalDevice;

use App\Http\Requests\MedicalDevice\MedicalDeviceRequest;
use App\Models\Doctors\doctor;
use App\Models\Doctors\DoctorTranslation;
use App\Models\medicalDevice\medicalDevice;
use App\Models\medicalDevice\MedicalDeviceTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;


class MedicalDeviceService
{
    private $MedicalDeviceModel;
    use GeneralTrait;

    public function __construct(MedicalDevice $MedicalDevice)
    {
        $this->MedicalDeviceModel=$MedicalDevice;
    }
    //get all medical devices
    public function get()
    {
        try {
            $MedicalDevice = $this->MedicalDeviceModel->paginate(5);
            return $this->returnData(' MedicalDevice', $MedicalDevice, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get medical device by id
    public function getById($id)
    {
        try{
        $MedicalDevice= $this->MedicalDeviceModel::find($id);
            if (is_null($MedicalDevice)){
                return $this->returnSuccessMessage('this MedicalDevice not found','done');
            }
            else{
                return $this->returnData(' MedicalDevice', $MedicalDevice,'done');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
// create new medical device
    public function create( MedicalDeviceRequest $request )
    {
        try {
            $allmedicaldevice = collect($request->medicaldevice)->all();
            DB::beginTransaction();
            $unTransmedicaldevice_id = medicalDevice::insertGetId([
                'image' => $request['image'],
                'is_approved' => $request['is_approved'],
                'is_active' => $request['is_active'],
            ]);
            if (isset($allmedicaldevice)) {
                foreach ($allmedicaldevice as $allmedicaldevices) {
                    $transmedicaldevice[] = [
                        'name' => $allmedicaldevices ['name'],
                        'description' => $allmedicaldevices ['description'],
                        'locale' => $allmedicaldevices['locale'],
                        'medical_device_id' => $unTransmedicaldevice_id,
                    ];
                }
             MedicalDeviceTranslation::insert($transmedicaldevice);
            }
            DB::commit();
            return $this->returnData('MedicalDevice',[$unTransmedicaldevice_id, $transmedicaldevice],'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//update old medical device
    public function update(MedicalDeviceRequest $request,$id)
    {
        try{
            $medicaldevice= MedicalDevice::find($id);
            if(!$medicaldevice)
                return $this->returnError('400', 'not found this medical Device');
            $allmedicaldevice = collect($request->medicaldevice)->all();
            if (!($request->has('medical_devices.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newmedicaldevice=MedicalDevice::where('medical_devices.id',$id)
                ->update([
                    'image' => $request['image'],
                    'is_approved' => $request['is_approved'],
                    'is_active' => $request['is_active'],
                ]);

            $ss=MedicalDeviceTranslation::where('medical_device_translation.medical_device_id',$id);
            $collection1 = collect($allmedicaldevice);
            $alldoctorlength=$collection1->count();
            $collection2 = collect($ss);

            $db_medicaldevice= array_values(MedicalDeviceTranslation::where('medical_device_translation.medical_device_id',$id)
                ->get()
                ->all());
            $dbmedicaldevice = array_values($db_medicaldevice);
            $request_medicaldevice= array_values($request->medicaldevice);
            foreach($dbmedicaldevice as $dbmedicaldevices){
                foreach($request_medicaldevice as $request_medicaldevices){
                    $values=MedicalDeviceTranslation::where('medical_device_translation.medical_Device_id',$id)
                        ->where('locale',$request_medicaldevices['locale'])
                        ->update([
                            'name' => $request_medicaldevices ['name'],
                            'description' => $request_medicaldevices ['description'],
                            'locale' => $request_medicaldevices['locale'],
                            'medical_device_id' => $id,
                        ]);
                }
            }
            DB::commit();
            return $this->returnData('MedicalDevice', [$db_medicaldevice,$values],'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//get medical device by his name
    public function search($name)
    {
        try{
             $MedicalDevice = DB::table('medical_device_translation')
                 ->where("name","like","%".$name."%")
                 ->get();
             if (! $MedicalDevice)
             {
                 return $this->returnError('400', 'not found this medicalDevice');
             }
             else
             {
                 return $this->returnData(' MedicalDevice',  $MedicalDevice,'done');
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
        $MedicalDevice= $this->MedicalDeviceModel::find($id);
            if (is_null($MedicalDevice)) {
                return $this->returnSuccessMessage('This MedicalDevice not found', 'done');
            }
            else
            {
                $MedicalDevice->is_active=0;
                $MedicalDevice->save();
                return $this->returnData(' MedicalDevice',  $MedicalDevice,'This MedicalDevice is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //get medical device where is_active value zero
    public function getTrashed()
    {
        try{
        $MedicalDevice= $this->MedicalDeviceModel::NotActive();
        return $this -> returnData(' MedicalDevice', $MedicalDevice,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }
    //change the is_active value to one
    public function restoreTrashed( $id)
    {
        try{
              $MedicalDevice=MedicalDevice::find($id);
              if (is_null($MedicalDevice)) {
                  return $this->returnSuccessMessage('This MedicalDevice not found', 'done');
              }
              else
              {
                  $MedicalDevice->is_active=1;
                  $MedicalDevice->save();
                  return $this->returnData('MedicalDevice',  $MedicalDevice,'This MedicalDevice is trashed Now');
              }
          }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    //Permanently delete the medical device  from the database
    public function delete($id)
    {
        try{
        $MedicalDevice = MedicalDevice::find($id);
            if ($MedicalDevice->is_active ==0) {
                $MedicalDevice->delete();
            $MedicalDevice->MedicalDeviceTranslation()->delete();
                return $this->returnData('MedicalDevice', $MedicalDevice, 'This MedicalDevice is deleted Now');
            }
            else{
                return $this->returnData( 'MedicalDevice', $MedicalDevice,'this medical device can not delete');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
//Find out which doctor is using the medical device by the medical device id
    public function doctormedicaldevice($id)
    {
        try {
            $MedicalDevice= MedicalDevice::with('doctor')->find($id);
            return $this->returnData('MedicalDevice', $MedicalDevice, 'This MedicalDevice is deleted Now');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
