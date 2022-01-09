<?php

namespace App\Http\Controllers\RestaurantManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantManager\RestaurantManagerRequest;
use App\Service\RestaurantManager\RestaurantManagerService;
use Illuminate\Http\Request;

class RestaurantManagerController extends Controller
{
    private $RestaurantManagerService;

    public function __construct(RestaurantManagerService $restaurantManagerService)
    {
        $this->RestaurantManagerService=$restaurantManagerService;
    }

    public function get()
    {
        return $this->RestaurantManagerService->get();
    }

    public function  getById($id)
    {
        return $this->RestaurantManagerService->getById($id);
    }

    public function getTrashed()
    {
        return  $this->RestaurantManagerService->getTrashed();
    }

    public function create(RestaurantManagerRequest  $request)
    {
        return $this->RestaurantManagerService->create($request);
    }

    public function update(RestaurantManagerRequest $request,$id)
    {
        return $this->RestaurantManagerService->update($request,$id);
    }
    public function search($name)
    {
        return  $this->RestaurantManagerService->search($name);
    }

    public function trash($id)
    {
        return $this->RestaurantManagerService->trash($id);
    }

    public function restoreTrashed($id)
    {
        return $this->RestaurantManagerService->restoreTrashed($id);
    }

    public function delete($id)
    {
        return $this->RestaurantManagerService->delete($id);
    }
}
