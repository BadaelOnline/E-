<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\PlanRequest;
use App\Service\PaymentMethod\PaymentMethodsServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    use GeneralTrait;
    private $plansService;
    public function __construct(PaymentMethodsServices $PaymentMethods)
    {
        $this->payments=$PaymentMethods;
    }
    public function getAll()
    {
        return $this->payments->getAll();
    }
    public function assigningToStore(Request $request,$storeId)
{
    return $this->payments->assigningToStore($request,$storeId);
}
    public function deleteFromStore($paymentId,$storeId)
    {
        return $this->payments->deleteFromStore($storeId);
    }
    public function getByStore($storeId)
    {
        return $this->payments->getByStore($storeId);
    }
    public function getById($id)
    {
        return $this->payments->getById($id);
    }
    public function getTrashed()
    {
        return $this->payments->getTrashed();
    }
    public function create(PlanRequest $request)
    {
        return $this->payments->create($request);
    }
    public function update(PlanRequest $request,$id)
    {
        return $this->payments->update($request,$id);
    }
    public function trash($id)
    {
        return $this->payments->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->payments->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->payments->delete($id);
    }
}
