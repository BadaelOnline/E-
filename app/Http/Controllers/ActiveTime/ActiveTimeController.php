<?php

namespace App\Http\Controllers\ActiveTime;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActiveTime\ActiveTimeRequest;
use App\Service\ActiveTime\ActiveTimeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GeneralTrait;

class ActiveTimeController extends Controller
{
    use GeneralTrait;
    private $ActiveTimeService;

    public function __construct(ActiveTimeService $ActiveTimeService )
    {
        $this->ActiveTimeService=$ActiveTimeService;
    }
    public function get()
    {
        return $this->ActiveTimeService->get();
    }

    public function  getById($id)
    {
        return $this->ActiveTimeService->getById($id);
    }

    public function create(ActiveTimeRequest $request)
    {
        return   $this->ActiveTimeService->create($request);
    }

    public function update(ActiveTimeRequest $request,$id)
    {
        return  $this->ActiveTimeService->update($request,$id);
    }

    public function trash($id)
    {
        return    $this->ActiveTimeService->trash($id);
    }
    public function getTrashed()
    {
        return  $this->ActiveTimeService->getTrashed();
    }

    public function restoreTrashed($id)
    {
        return   $this->ActiveTimeService->restoreTrashed($id);
    }

    public function delete($id)
    {
        return    $this->ActiveTimeService->delete($id);
    }

}
