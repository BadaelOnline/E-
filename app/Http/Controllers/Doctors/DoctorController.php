<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctors\DoctorRequest;
use App\Service\Doctors\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    private $DoctorService;

    public function __construct(DoctorService $DoctorService )
    {
        $this->DoctorService=$DoctorService;
    }

    public function get()
    {
        return $this->DoctorService->get();
    }

    public function  getById($id)
    {
        return $this->DoctorService->getById($id);
    }

    public function getTrashed()
    {
        return  $this->DoctorService->getTrashed();
    }

    public function create(DoctorRequest  $request)
    {
        return $this->DoctorService->create($request);
    }

    public function update(DoctorRequest $request,$id)
    {
        return $this->DoctorService->update($request,$id);
    }
    public function search($name)
    {
        return  $this->DoctorService->search($name);
    }

    public function trash($id)
    {
        return $this->DoctorService->trash($id);
    }

    public function restoreTrashed($id)
    {
        return $this->DoctorService->restoreTrashed($id);
    }

    public function delete($id)
    {
        return $this->DoctorService->delete($id);
    }

    public function DoctorSocialMedia($id)
    {
        return $this->DoctorService->DoctorSocialMedia($id);
    }

    public function doctormedicaldevice($id)
    {
        return $this->DoctorService->doctormedicaldevice($id);
    }

    public function doctorhospital($id)
    {
        return $this->DoctorService->doctorhospital($id);
    }

    public function doctorappointment($id)
    {
        return $this->DoctorService->doctorappointment($id);
    }

    public function doctorclinic($id)
    {
        return $this->DoctorService->doctorclinic($id);
    }

    public function Patient($id)
    {
        return $this->DoctorService->Patient($id);
    }
    public function DoctorRate($id)
    {
        return $this->DoctorService->DoctorRate($id);
    }
    public function DoctorSpecialty($id)
    {
        return $this->DoctorService->DoctorSpecialty($id);
    }
    //______________________________insert function __________________________________________//

     public function InsertDoctorHospital(Request $request)
     {
         return $this->DoctorService->InsertDoctorHospital($request);
     }
     public function InsertDoctorMedicalDevice(Request $request)
     {
         return $this->DoctorService->InsertDoctorMedicalDevice($request);
     }
     public function InsertDoctorSpecialty(Request $request)
     {
         return $this->DoctorService->InsertDoctorSpecialty($request);
     }
     public function InsertDoctorPatient(Request $request)
     {
         return $this->DoctorService->InsertDoctorPatient($request);
     }
}
