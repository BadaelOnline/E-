<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\PlanRequest;
use App\Service\Orders\OrderServices;
use App\Service\PaymentMethod\PaymentMethodsServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    use GeneralTrait;
    private $OrderServices;
    public function __construct(OrderServices $OrderServices)
    {
        $this->orders=$OrderServices;
    }
    public function getAll()
    {
        return $this->orders->getAll();
    }
    public function assigningToStore(Request $request,$storeId)
    {
        return $this->orders->assigningToStore($request,$storeId);
    }
    public function deleteFromStore($paymentId,$storeId)
    {
        return $this->orders->deleteFromStore($storeId);
    }
    public function getByStore($storeId)
    {
        return $this->orders->getByStore($storeId);
    }
    public function getById($id)
    {
        return $this->orders->getById($id);
    }
    public function getTrashed()
    {
        return $this->orders->getTrashed();
    }
    public function create(Request $request)
    {
        return $this->orders->create($request);
    }
    public function update(PlanRequest $request,$id)
    {
        return $this->orders->update($request,$id);
    }
    public function trash($id)
    {
        return $this->orders->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->orders->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->orders->delete($id);
    }
}
