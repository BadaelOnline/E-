<?php

namespace App\Service\StoresOrder;

use App\Models\Stores\Store;
use App\Models\Products\Product;
use App\Models\Stores\StoreProduct;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class StoresOrderService
{
    use GeneralTrait;
    private $StoresProductsService;
    private $storeProductModel;
    private $productModel;
    private $storeModel;

    public function __construct(StoreProduct $storeProduct,Product $product,Store $store )
    {
        $this->storeProductModel=$storeProduct;
        $this->productModel=$product;
        $this->storeModel=$store;
    }
    public function getChekOutId(Request $request)
    {
        $url = "https://test.oppwa.com/v1/checkouts";
        $data = "entityId=8a8294174b7ecb28014b9699220015ca" .
            "&amount=".$request->total.
            "&currency=EUR" .
            "&paymentType=DB";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGE4Mjk0MTc0YjdlY2IyODAxNGI5Njk5MjIwMDE1Y2N8c3k2S0pzVDg='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
         return $res =json_decode($responseData,true);
//        return $res['id'];
//        return $response = $this->returnData('Products', $responseData, 'done');

    }

}
