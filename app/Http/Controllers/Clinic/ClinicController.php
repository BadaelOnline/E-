<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clinic\ClinicRequest;
use App\Service\Clinic\ClinicService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClinicController extends Controller
{
    use GeneralTrait;
    private $ClinicService;

    public function __construct(ClinicService $ClinicService)
    {
        $this->ClinicService=$ClinicService;
    }

    public function get()
    {
        return $this->ClinicService->get();
    }

    public function  getById($id)
    {
        return $this->ClinicService->getById($id);
    }

    public function create(ClinicRequest $request)
    {
        return $this->ClinicService->create($request);
    }

    public function update(ClinicRequest $request,$id)
    {
        return $this->ClinicService->update($request,$id);
    }
    public function search($name)
    {
        return  $this->ClinicService->search($name);
    }

    public function trash($id)
    {
        return  $this->ClinicService->trash($id);
    }
    public function getTrashed()
    {
        return  $this->ClinicService->getTrashed();
    }

    public function restoreTrashed($id)
    {
        return  $this->ClinicService->restoreTrashed($id);
    }

    public function delete($id)
    {
        return $this->ClinicService->delete($id);
    }
}
