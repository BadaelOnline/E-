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

    public function __construct(StoresProductsService $StoresProducts)
    {
        $this->StoresProductsService = $StoresProducts;
    }

    public function insertProductToStore(Request $request, $store_id)
    {
        return $this->StoresProductsService->insertProductToStore($request, $store_id);
    }

    public function updateProductInStore(Request $request, $store_id, $product_id)
    {
        return $this->StoresProductsService->updateProductInStore($request, $store_id, $product_id);
    }

    public function viewStoresHasProduct($id)
    {
        return $this->StoresProductsService->viewStoresHasProduct($id);
    }
    public function viewProductByCategory($category_id)
    {
        return $this->StoresProductsService->viewProductByCategory($category_id);
    }
    public function viewProductByCategoryDetails($product_id)
    {
        return $this->StoresProductsService->viewProductByCategoryDetails($product_id);
    }

    public function viewProductsInStore($store_id)
    {
        return $this->StoresProductsService->viewProductsInStore($store_id);
    }

    public function viewProductsDetailsInStore($product_id)
    {
        return $this->StoresProductsService->viewProductsDetailsInStore($product_id);
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

    public function updateMultyProductsPricesInStore(Request $request, $store_id)
    {
        return $this->StoresProductsService->updateMultyProductsPricesInStore($request, $store_id);
    }

    public function updatePricesPyRatio(Request $request, $store_id)
    {
        return $this->StoresProductsService->updatePricesPyRatio($request, $store_id);
    }

    public function deleteProductFromStore($product_id)
    {
        return $this->StoresProductsService->deleteProductFromStore($product_id);
    }
}
