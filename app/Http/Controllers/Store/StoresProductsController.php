<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use App\Http\Controllers\Controller;
use App\Service\Stores\StoresProductsService;

class StoresProductsController extends Controller
{
    use GeneralTrait;
    private $StoresProductsService;
    public function __construct(StoresProductsService  $StoresProducts)
    {
        $this->StoresProductsService=$StoresProducts;
    }
    public function insertProductToStore(Request $request)
    {
        return $this->StoresProductsService->insertProductToStore($request);
    }
    public function updateProductInStore(Request $request,$id)
    {
        return $this->StoresProductsService->updateProductInStore($request,$id);
    }
    public function viewStoresHasProduct($id)
    {
        return $this->StoresProductsService->viewStoresHasProduct($id);
    }
    public function viewProductsInStore($id)
    {
        return $this->StoresProductsService->viewProductsInStore($id);
    }
    public function hiddenProductByQuantity($id)
    {
        return $this->StoresProductsService->hiddenProductByQuantity($id);
    }
    public function rangeOfPrice($id)
    {
        return $this->StoresProductsService->rangeOfPrice($id);
    }
    public function getAllProductInStore($id)
    {
        return $this->StoresProductsService->getAllProductInStore($id);
    }
    public function updateMultyProductsPricesInStore(Request $request,$store_id)
    {
        return $this->StoresProductsService->updateMultyProductsPricesInStore($request,$store_id);
    }
    public function updatePricesPyRatio(Request $request,$store_id)
    {
        return $this->StoresProductsService->updatePricesPyRatio($request,$store_id);
    }
}
