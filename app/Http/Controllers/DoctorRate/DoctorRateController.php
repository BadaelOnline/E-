<?php

namespace App\Http\Controllers\DoctorRate;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRate\DoctorRateRequest;
use App\Service\DoctorRate\DoctorRateService;

class DoctorRateController extends Controller
{
    private $DoctorRateService;

    public function __construct(DoctorRateService $DoctorRateService )
    {
        $this->DoctorRateService=$DoctorRateService;
    }

    public function get()
    {
        return $this->DoctorRateService->get();
    }

    public function  getById($id)
    {
        return  $this->DoctorRateService->getById($id);
    }

    public function getTrashed()
    {
        return  $this->DoctorRateService->getTrashed();
    }

    public function create(DoctorRateRequest $request)
    {
        return  $this->DoctorRateService->create($request);
    }

    public function update(DoctorRateRequest $request,$id)
    {
        return $this->DoctorRateService->update($request,$id);
    }

    public function trash($id)
    {
        return $this->DoctorRateService->trash($id);
    }

    public function restoreTrashed($id)
    {
        return $this->DoctorRateService->restoreTrashed($id);
    }

    public function delete($id)
    {
        return $this->DoctorRateService->delete($id);
    }
}
