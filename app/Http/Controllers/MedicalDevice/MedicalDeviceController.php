<?php

namespace App\Http\Controllers\MedicalDevice;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalDevice\MedicalDeviceRequest;
use App\Service\MedicalDevice\MedicalDeviceService;

class MedicalDeviceController extends Controller
{
    private $MedicalDeviceService;

    public function __construct(MedicalDeviceService $MedicalDeviceService )
    {
        $this->MedicalDeviceService =$MedicalDeviceService;
    }
    public function get()
    {
        return$this->MedicalDeviceService->get();
    }
    public function  getById($id)
    {
        return $this->MedicalDeviceService->getById($id);
    }
    public function getTrashed()
    {
        return $this->MedicalDeviceService->getTrashed();
    }
    public function create(MedicalDeviceRequest $request)
    {
        return $this->MedicalDeviceService->create($request);
    }
    public function update(MedicalDeviceRequest $request,$id)
    {
        return $this->MedicalDeviceService->update($request,$id);
    }
    public function search($name)
    {
        return $this->MedicalDeviceService->search($name);
    }
    public function trash($id)
    {
        return $this->MedicalDeviceService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->MedicalDeviceService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return  $this->MedicalDeviceService->delete($id);
    }
    public function getdoctor($id)
    {
        return  $this->MedicalDeviceService->doctormedicaldevice($id);
    }

}
