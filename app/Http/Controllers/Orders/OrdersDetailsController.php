<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\PlanRequest;
use App\Service\Orders\OrderDetailsService;
use App\Service\PaymentMethod\PaymentMethodsServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class OrdersDetailsController extends Controller
{
    use GeneralTrait;
    private $details;
    public function __construct(OrderDetailsService $details)
    {
        $this->details=$details;
    }
    public function getAll()
    {
        return $this->details->getAll();
    }
    public function assigningToStore(Request $request,$storeId)
    {
        return $this->details->assigningToStore($request,$storeId);
    }
    public function deleteFromStore($paymentId,$storeId)
    {
        return $this->details->deleteFromStore($storeId);
    }
    public function getByStore($storeId)
    {
        return $this->details->getByStore($storeId);
    }
    public function getById($id)
    {
        return $this->details->getById($id);
    }
    public function getTrashed()
    {
        return $this->details->getTrashed();
    }
    public function create(PlanRequest $request)
    {
        return $this->details->create($request);
    }
    public function update(PlanRequest $request,$id)
    {
        return $this->details->update($request,$id);
    }
    public function trash($id)
    {
        return $this->details->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->details->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->details->delete($id);
    }
}
