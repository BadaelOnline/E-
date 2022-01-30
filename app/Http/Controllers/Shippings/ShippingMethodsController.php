<?php

namespace App\Http\Controllers\Shippings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\PlanRequest;
use App\Service\ShippingMethods\ShippingMethodsServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ShippingMethodsController extends Controller
{
    use GeneralTrait;
    private $plansService;
    public function __construct(ShippingMethodsServices $ShippingMethods)
    {
        $this->shipping=$ShippingMethods;
    }
    public function getAll()
    {
        return $this->shipping->getAll();
    }
    public function assigningToStore(Request $request,$storeId)
    {
        return $this->shipping->assigningToStore($request,$storeId);
    }
    public function deleteFromStore($paymentId,$storeId)
    {
        return $this->shipping->deleteFromStore($storeId);
    }
    public function getByStore($storeId)
    {
        return $this->shipping->getByStore($storeId);
    }
    public function getById($id)
    {
        return $this->shipping->getById($id);
    }
    public function getTrashed()
    {
        return $this->shipping->getTrashed();
    }
    public function create(PlanRequest $request)
    {
        return $this->shipping->create($request);
    }
    public function update(PlanRequest $request,$id)
    {
        return $this->shipping->update($request,$id);
    }
    public function trash($id)
    {
        return $this->shipping->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->shipping->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->shipping->delete($id);
    }
}
