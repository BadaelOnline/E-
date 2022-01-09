<?php

namespace App\Http\Controllers\RestaurantType;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantType\RestaurantTypeRequest;
use App\Service\RestaurantType\RestaurantTypeService;

class RestaurantTypeController extends Controller
{
    private $RestaurantTypeService;


    public function __construct(RestaurantTypeService $RestaurantTypeService)
    {
        $this->RestaurantTypeService=$RestaurantTypeService;
    }
    public function get()
    {
        return $this->RestaurantTypeService->get();
    }
    public function  getById($id)
    {
        return $this->RestaurantTypeService->getById($id);
    }
    public function getTrashed()
    {
        return$this->RestaurantTypeService->getTrashed();
    }
    public function create(RestaurantTypeRequest $request)
    {
        return $this->RestaurantTypeService->create($request);
    }
    public function update(RestaurantTypeRequest $request,$id)
    {
        return $this->RestaurantTypeService->update($request,$id);
    }
    public function search($restaurant_type_title)
    {
        return $this->RestaurantTypeService->search($restaurant_type_title);
    }
    public function trash($id)
    {
        return $this->RestaurantTypeService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->RestaurantTypeService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->RestaurantTypeService->delete($id);
    }

    public function getRestaurant($id)
    {
        return $this->RestaurantTypeService->getRestaurant($id);
    }
}
