<?php

namespace App\Http\Controllers\Stores_Orders;

use App\Http\Controllers\Controller;
use App\Models\Products\Product;
use App\Models\Stores\Store;
use App\Models\Stores_Orders\Stores_Order;
use App\Service\StoresOrder\StoresOrderService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class StoresOrderController extends Controller
{
    use GeneralTrait;
    private $StoresOrderService;
    private $storeOrderModel;
    private $productModel;
    private $storeModel;

    public function __construct(Stores_Order $storeOrderModel,
    Product $product,
    Store $store,
    StoresOrderService $StoresOrderService )
    {
        $this->storeOrderModel=$storeOrderModel;
        $this->productModel=$product;
        $this->storeModel=$store;
        $this->storesOrderService=$StoresOrderService;

    }
    public function getChekOutId(Request $request)
    {
        return $this->storesOrderService->getChekOutId($request);
    }



}
