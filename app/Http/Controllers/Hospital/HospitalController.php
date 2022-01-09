<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\HospitalRequest;
use App\Service\Hospital\HospitalService;

class HospitalController extends Controller
{
    private $HospitalService;

    public function __construct(HospitalService $HospitalService )
    {
        $this->HospitalService=$HospitalService;
    }
    public function get()
    {
        return $this->HospitalService->get();
    }
    public function  getById($id)
    {
        return $this->HospitalService->getById($id);
    }
    public function getTrashed()
    {
        return$this->HospitalService->getTrashed();
    }
    public function create(HospitalRequest $request)
    {
        return $this->HospitalService->create($request);
    }
    public function update(HospitalRequest $request,$id)
    {
        return $this->HospitalService->update($request,$id);
    }
    public function search($name)
    {
        return $this->HospitalService->search($name);
    }
    public function trash($id)
    {
        return $this->HospitalService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->HospitalService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->HospitalService->delete($id);
    }
    //get all the doctors who work in the hospital according to her name
    public function hospitalsDoctor($id)
    {
        return $this->HospitalService->hospitalsDoctor($id);
    }
}
