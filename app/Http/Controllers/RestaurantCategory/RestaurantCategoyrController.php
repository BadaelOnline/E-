<?php

namespace App\Http\Controllers\RestaurantCategory;

use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantCategory\RestaurantCategoryRequest;
use App\Service\RestaurantCategory\RestaurantCategoryService;
use Illuminate\Http\Request;


class RestaurantCategoyrController extends Controller
{
    private $RestaurantCategoryService;

    public function __construct(RestaurantCategoryService $RestaurantCategory )
    {
        $this->RestaurantCategoryService=$RestaurantCategory;

    }
    public function get()
    {
        return $this->RestaurantCategoryService->get();
    }
    public function  getById($id)
    {
        return $this->RestaurantCategoryService->getById($id);
    }
    public function getTrashed()
    {
        return$this->RestaurantCategoryService->getTrashed();
    }
    public function create(RestaurantCategoryRequest $request)
    {
        return $this->RestaurantCategoryService->create($request);
    }
    public function update(RestaurantCategoryRequest $request,$id)
    {
        return $this->RestaurantCategoryService->update($request,$id);
    }
    public function search($name)
    {
        return $this->RestaurantCategoryService->search($name);
    }
    public function trash($id)
    {
        return $this->RestaurantCategoryService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->RestaurantCategoryService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->RestaurantCategoryService->delete($id);
    }
    public function getRestaurant($id)
    {
        return $this->RestaurantCategoryService->getRestaurant($id);
    }
    public function getProduct($id)
    {
        return $this->RestaurantCategoryService->getProduct($id);
    }
    //_________________insert_____________________//

    public function insertToRestaurantcategoryRestaurantproduct(Request $request)
    {
        return $this->RestaurantCategoryService->insertToRestaurantcategoryRestaurantproduct($request);
    }
    public function insertToRestaurantcategoryItem(Request $request)
    {
        return $this->RestaurantCategoryService->insertToRestaurantcategoryItem($request);
    }
}
