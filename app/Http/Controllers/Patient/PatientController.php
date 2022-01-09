<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\PatientRequest;
use App\Service\Patient\PatientService;

class PatientController extends Controller
{
    private $PatientService;

    public function __construct(PatientService $PatientService )
    {
        $this->PatientService=$PatientService;
    }


    public function getAll()
    {
        return $this->PatientService->getAll();
    }
    public function getById($id)
    {
        return $this->PatientService->getById($id);
    }
    public function create(PatientRequest $request)
    {
        return $this->PatientService->create($request);
    }
    public function update(PatientRequest $request,$id)
    {
        return $this->PatientService->update($request,$id);
    }

    public function trash($id)
    {
        return $this->PatientService->trash($id);
    }
    public function getTrashed()
    {
        return $this->PatientService->getTrashed();
    }
    public function restoreTrashed($id)
    {
        return $this->PatientService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->PatientService->delete($id);
    }

}
